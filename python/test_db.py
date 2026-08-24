import psycopg2

conn = psycopg2.connect(
    host="127.0.0.1",
    port="5432",
    database="Hydroponic_EnvControl",
    user="postgres",
    password="farah1234"
)

print("CONNECTED")

conn.close()