import warnings
warnings.filterwarnings("ignore")

import pandas as pd
import numpy as np
import json
import sys

# ==========================================
# RECEIVE DATA FROM LARAVEL
# ==========================================

json_file = sys.argv[1]
selected_session = sys.argv[2]
selected_date = sys.argv[3]

with open(json_file, "r", encoding="utf-8") as f:
    sensor_data = json.load(f)

df = pd.DataFrame(sensor_data)

# ==========================================
# NO DATA
# ==========================================

if len(df) == 0:

    print(json.dumps({
        "status": "nodata"
    }))

    sys.exit()

# ==========================================
# DATA CLEANING
# ==========================================

df['timestamp'] = pd.to_datetime(df['timestamp'])

df['dht_temp'] = pd.to_numeric(df['dht_temp'])
df['ph'] = pd.to_numeric(df['ph'])
df['tds'] = pd.to_numeric(df['tds'])
df['water_level'] = pd.to_numeric(df['water_level'])

# ==========================================
# BUILD DAILY SESSION AVERAGES
# ==========================================

df['date'] = df['timestamp'].dt.date

df['session'] = np.where(
    df['timestamp'].dt.hour < 12,
    'am',
    'pm'
)

daily = df.groupby(
    ['date', 'session']
).agg({

    'dht_temp':'mean',
    'ph':'mean',
    'tds':'mean',
    'water_level':'mean'

}).reset_index()

# ==========================================
# SIMPLE AM-PM PREDICTION
# ==========================================

def predict_relationship(
    current_value,
    source_series,
    target_series
):

    if len(source_series) < 2:

        return round(
            float(current_value),
            2
        )

    slope, intercept = np.polyfit(
        source_series,
        target_series,
        1
    )

    prediction = (
        slope * current_value
    ) + intercept

    return round(
        float(prediction),
        2
    )


# ==========================================
# CURRENT VALUES
# ==========================================

selected_date = pd.to_datetime(
    selected_date
).date()

current_row = daily[
    (daily['date'] == selected_date)
    &
    (daily['session'] == selected_session)
]

if len(current_row) == 0:

    print(json.dumps({
        "status":"nodata"
    }))

    sys.exit()

current_row = current_row.iloc[0]

current = {

    "temperature":
        round(float(current_row['dht_temp']), 2),

    "ph":
        round(float(current_row['ph']), 2),

    "tds":
        round(float(current_row['tds']), 2),

    "water":
        round(float(current_row['water_level']), 0)

}

# ==========================================
# FORECAST VALUES
# ==========================================

# ==========================================
# TRAIN SESSION RELATIONSHIP
# ==========================================

# ==========================================
# SAME SESSION NEXT-DAY PREDICTION
# ==========================================

pairs = []

dates = sorted(daily['date'].unique())

for i in range(len(dates) - 1):

    current_date = dates[i]
    next_date = dates[i + 1]

    current_session_row = daily[
        (daily['date'] == current_date)
        &
        (daily['session'] == selected_session)
    ]

    next_session_row = daily[
        (daily['date'] == next_date)
        &
        (daily['session'] == selected_session)
    ]

    if len(current_session_row) and len(next_session_row):

        pairs.append({

            'temp_today':
                current_session_row.iloc[0]['dht_temp'],

            'temp_next':
                next_session_row.iloc[0]['dht_temp'],

            'ph_today':
                current_session_row.iloc[0]['ph'],

            'ph_next':
                next_session_row.iloc[0]['ph'],

            'tds_today':
                current_session_row.iloc[0]['tds'],

            'tds_next':
                next_session_row.iloc[0]['tds'],

            'water_today':
                current_session_row.iloc[0]['water_level'],

            'water_next':
                next_session_row.iloc[0]['water_level']

        })

pair_df = pd.DataFrame(pairs)

if len(pair_df) == 0:

    forecast = current.copy()

else:

    forecast = {

        "temperature":
            predict_relationship(
                current["temperature"],
                pair_df["temp_today"],
                pair_df["temp_next"]
            ),

        "ph":
            predict_relationship(
                current["ph"],
                pair_df["ph_today"],
                pair_df["ph_next"]
            ),

        "tds":
            predict_relationship(
                current["tds"],
                pair_df["tds_today"],
                pair_df["tds_next"]
            ),

        "water":
            min(
                100,
                max(
                    0,
                    round(
                        predict_relationship(
                            current["water"],
                            pair_df["water_today"],
                            pair_df["water_next"]
                        )
                    )
                )
            )

    }

# ==========================================
# RISK ANALYSIS
# ==========================================

risk = []


# TEMPERATURE

if forecast["temperature"] > 28 or forecast["temperature"] < 18:

    risk.append({

        "parameter":"Temperature",
        "status":"Critical",
        "message":"Temperature may exceed safe range."

    })


elif forecast["temperature"] > 26 or forecast["temperature"] < 20:

    risk.append({

        "parameter":"Temperature",
        "status":"Warning",
        "message":"Temperature may move outside optimal range."

    })

# PH

if forecast["ph"] < 5.5 or forecast["ph"] > 6.5:

    risk.append({

        "parameter":"pH",
        "status":"Critical",
        "message":"pH may reach critical level."

    })


elif forecast["ph"] < 5.8 or forecast["ph"] > 6.2:

    risk.append({

        "parameter":"pH",
        "status":"Warning",
        "message":"pH may move outside optimal range."

    })

# TDS

if forecast["tds"] < 600 or forecast["tds"] > 1500:

    risk.append({

        "parameter":"TDS",
        "status":"Critical",
        "message":"Nutrient concentration may become critical."

    })


elif forecast["tds"] < 800 or forecast["tds"] > 1200:

    risk.append({

        "parameter":"TDS",
        "status":"Warning",
        "message":"Nutrient concentration may move outside optimal range."

    })


# WATER LEVEL

if forecast["water"] <= 0:

    risk.append({

        "parameter":"Water Level",
        "status":"Critical",
        "message":"Water level may become critically low."

    })


elif forecast["water"] <= 1:

    risk.append({

        "parameter":"Water Level",
        "status":"Warning",
        "message":"Water level may become low."

    })


# ==========================================
# OVERALL RISK LEVEL
# ==========================================

critical_count = 0
warning_count = 0

for item in risk:

    if item["status"] == "Critical":
        critical_count += 1

    elif item["status"] == "Warning":
        warning_count += 1


if critical_count >= 1:

    overall_risk = "HIGH"

elif warning_count >= 2:

    overall_risk = "MEDIUM"

else:

    overall_risk = "LOW"


# ==========================================
# FINAL RESULT
# ==========================================

result = {

    "status":"success",

    "current":current,

    "forecast":forecast,

    "risk":risk,

    "overall_risk":overall_risk

}

print(json.dumps(result))