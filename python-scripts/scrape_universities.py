import requests
import mysql.connector
import time

def extract_location(address):
    try:
        if ',' in address:
            location_part = address.split(',')[1].strip()
            location = ' '.join(part for part in location_part.split() if not part.replace(' ', '').isdigit())
            return location
        return ''
    except Exception as e:
        print(f"Chyba při extrahování města: {e}")
        return ''

def insert_into_database(uni):
    try:
        connection = mysql.connector.connect(
            host='89.168.43.83',
            user='michaelka',
            password='michaelka1',
            database='michaelka_klauzury',
            charset='utf8mb4',
            collation='utf8mb4_general_ci'
        )

        if connection.is_connected():
            cursor = connection.cursor()
            location = extract_location(uni['address'])

            query = """INSERT INTO universities 
                (name, address, location, website, facebook, twitter, instagram, youtube, about, is_disabled)
                VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"""
            values = (
                uni['name'],
                uni['address'],
                location,
                uni['website'],
                uni.get('facebook', ''),
                uni.get('twitter', ''),
                uni.get('instagram', ''),
                uni.get('youtube', ''),
                uni['about'],
                uni['isDisabled']
            )
            
            cursor.execute(query, values)
            connection.commit()
            print(f"✅ {uni['name']} bylo vloženo.")
    except mysql.connector.Error as err:
        print(f"❌ DB chyba: {err}")
    finally:
        if 'connection' in locals() and connection.is_connected():
            connection.close()

# ⬇️ CYKLUS přes ID institucí
for id_institution in range(1, 72):
    url = f"https://portal.studyin.cz/en/find-your-institution/get-data-detail?idInstitution={id_institution}"
    response = requests.get(url)
    
    if response.status_code == 200:
        data = response.json()
        if data.get("name"):  # Kontrola že je to validní instituce
            insert_into_database(data)
        else:
            print(f"⚠️ ID {id_institution} neobsahuje platná data.")
    else:
        print(f"❌ Chyba při načítání ID {id_institution}: {response.status_code}")
    
    time.sleep(0.5)  # malý delay, ať je to šetrné
