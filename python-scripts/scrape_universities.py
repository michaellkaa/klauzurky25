import requests
import mysql.connector

# URL pro získání JSON dat
url = "https://portal.studyin.cz/en/find-your-institution/get-data-detail?idInstitution=29"

response = requests.get(url)

# Funkce pro extrahování města z adresy
def extract_location(address):
    try:
        if ',' in address:
            # Oddělit část za čárkou (kde bývá PSČ a město)
            location_part = address.split(',')[1].strip()
            # Odstranit PSČ (čísla) a ponechat jen název města
            location = ' '.join(part for part in location_part.split() if not part.replace(' ', '').isdigit())
            return location
        return ''
    except Exception as e:
        print(f"Chyba při extrahování města: {e}")
        return ''

# Kontrola, zda odpověď byla úspěšná
if response.status_code == 200:
    data = response.json()  # Převeďte odpověď na JSON
    print(f"Data z API: {data}")  # Zobrazí všechna data, která dostaneme z API

    # Funkce pro vložení do databáze
    def insert_into_database(uni):
        try:
            print("4idk")

            # Připojení k MariaDB
            connection = mysql.connector.connect(
                host='89.168.43.83',
                user='michaelka',
                password='michaelka1',
                database='michaelka_klauzury',
                #charset='utf8mb4'
                charset='utf8mb4',  # force compatible charset
                collation='utf8mb4_general_ci'  # optional; some drivers ignore this
            )
            print("idk")

            if connection.is_connected():
                cursor = connection.cursor()

                # Extrahuj město z adresy
                location = extract_location(uni['address'])
                print(f"Město: {location}")

                # SQL dotaz včetně sloupce 'town' (musí existovat v DB)
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
                print("Data bylo úspěšně vloženo!")

        except mysql.connector.Error as err:
            print(f"Chyba při připojení do databáze: {err}")
        finally:
            if 'connection' in locals() and connection.is_connected():
                connection.close()
                print("Spojení bylo uzavřeno.")
    
    insert_into_database(data)

else:
    print(f"Chyba při získávání dat z API: {response.status_code}")