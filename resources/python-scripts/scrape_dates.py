from bs4 import BeautifulSoup
import requests
import re
import json
import mysql.connector
import time
from datetime import datetime #ja musim menit jeste datum D:

def insert_into_database(dates):
    try:
        connection = mysql.connector.connect(
            host='89.168.43.83',
            user='michaelka',
            password='michaelka1',
            database='michaelka_klauzury_html',
            charset='utf8mb4',
            collation='utf8mb4_general_ci'
        )
        if connection.is_connected():
            cursor = connection.cursor()

            query = """
                INSERT INTO events
                (university, faculty, date, type)
                VALUES (%s, %s, %s, %s)
            """
            values = (
                dates.get('university', ''),
                dates.get('faculty', ''),
                dates.get('date', ''),
                dates.get('type', ''),
                
            )

            cursor.execute(query, values)
            connection.commit()
    except Exception as e:
        print(f"Chyba při ukládání: {e}")
    finally:
        if 'connection' in locals() and connection.is_connected():
            cursor.close()
            connection.close()

url = "https://www.vysokeskoly.com/terminy-prijimacek/"
response = requests.get(url)
response.encoding = 'windows-1250'

soup = BeautifulSoup(response.text, "html.parser")
rows = soup.select("table tr")

aktualni_datum = None

for row in rows:
    classes = row.get("class", [])

    if "dateHeader" in classes:
        td = row.find("td")
        if td:
            td_text = td.get_text(strip=True)
            match = re.match(r"(\d{1,2}\.\d{1,2}\.\d{4})", td_text)
            if match:
                aktualni_datum = match.group(1)
            else:
                a_tag = td.find("a", attrs={"name": True})
                if a_tag:
                    aktualni_datum = a_tag["name"]

    else:
        tds = row.find_all("td")
        if len(tds) == 2 and aktualni_datum:
            univ_a = tds[0].find("a")
            univerzita_nazev = univ_a.text.strip() if univ_a else tds[0].text.strip()

            fakulta_a = tds[1].find("a")
            fakulta_text = fakulta_a.text.strip() if fakulta_a else tds[1].text.strip()

            fakulta_nazev = re.sub(r"\s*\(.*\)", "", fakulta_text)

            try:
                date_obj = datetime.strptime(aktualni_datum, "%d.%m.%Y")
                iso_date = date_obj.strftime("%Y-%m-%d")
            except ValueError as e:
                print(f"Neplatné datum: {aktualni_datum} – {e}")
                continue  # přeskočí tento záznam

            event_data = {
                'university': univerzita_nazev,
                'faculty': fakulta_nazev,
                'date': iso_date,
                'type': "Přijímací zkoušky"
            }

            insert_into_database(event_data)
            print(f"Uloženo: {event_data}")
            time.sleep(0.1)



