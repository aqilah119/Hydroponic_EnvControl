import pandas as pd
import numpy as np

# Read original dataset
df = pd.read_csv("sensor_logs.csv")

# =====================
# LETTUCE DATASET
# =====================

lettuce = df.copy()

lettuce["DHT_temp"] = (
    df["DHT_temp"] - 2
    + np.random.uniform(-0.3, 0.3, len(df))
)

lettuce["TDS"] = (
    df["TDS"] * 0.70
    + np.random.uniform(-20, 20, len(df))
)

lettuce["pH"] = (
    df["pH"] - 0.2
    + np.random.uniform(-0.1, 0.1, len(df))
)

lettuce["water_level"] = (
    df["water_level"]
    + np.random.uniform(-2, 2, len(df))
)

lettuce["water_level"] = lettuce["water_level"].clip(lower=0)


# =====================
# CHILI DATASET
# =====================

chili = df.copy()

chili["DHT_temp"] = (
    df["DHT_temp"] + 3
    + np.random.uniform(-0.3, 0.3, len(df))
)

chili["TDS"] = (
    df["TDS"] * 1.35
    + np.random.uniform(-30, 30, len(df))
)

chili["pH"] = (
    df["pH"] + 0.3
    + np.random.uniform(-0.1, 0.1, len(df))
)

chili["water_level"] = (
    df["water_level"]
    + np.random.uniform(-2, 2, len(df))
)

chili["water_level"] = chili["water_level"].clip(lower=0)

print(df["add_water"].head())
print(df["add_water"].dtype)


# Save files
lettuce.to_csv("lettuce_dataset.csv", index=False)
chili.to_csv("chili_dataset.csv", index=False)

print("Datasets generated successfully!")