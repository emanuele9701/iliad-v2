# Documentazione del Progetto: Sistema di Monitoraggio Ordini

## Introduzione
Questo progetto ha come obiettivo la creazione di un sistema per monitorare e gestire gli ordini degli utenti. Il sistema include un'interfaccia frontend per la visualizzazione e la gestione degli ordini, insieme a un backend RESTful per la gestione dei dati.

## Requisiti di Sistema
- **Software**:
    - PHP (versione 8.2)
      - estensione php-gd
    - Composer
    - Docker (per l'ambiente containerizzato)
- **Framework**
    - Laravel v10 
- **Hardware**:
    - Un computer con almeno 4 GB di RAM

## Setup del Progetto
1. **Clonare il repository**:
   ```bash
   git clone https://github.com/emanuele9701/iliad-v2.git
   cd iliad-v2
   ```
Installare le dipendenze:

   ```bash
composer update
   ```
Configurazione dell'ambiente:

Creare un file .env basato su .env.example e configurare le variabili necessarie, inclusi:
```bash
DB_DATABASE: Nome del database da creare
DB_USERNAME: Nome utente del database da creare
DB_PASSWORD: Password del database da creare
DB_HOST: indicare mysql
WWWUSER: ID utente per il web server ( io ho usato 1000 )
WWWGROUP: ID gruppo per il web server ( io ho usato 1000 )
APP_PORT: è il valore che userà docker per mettere in ascolto il server. Io ho usato 8080.
APP_URL: Impostare indirizzo ip/url dedicato a questo progetto.  ( includendo il protocollo http / https )
```

## Esecuzione del Progetto
Per avviare il progetto in un ambiente Docker, eseguire il seguente comando:

```bash
docker-compose up --build
```
La build ha uno script sh che attende 60 prima di procedere a creare il db del progetto. Questo per concedere la possibilità di accendersi correttamente ( il db ). 

Appena nella console trovate un messaggio simile a questo
```bash
INFO  Server running on [http://0.0.0.0:80].
```
Allora il server è pronto ed è possibile  accede all'interfaccia frontend visitando http://localhost:<APP_PORT> nel browser. ( ad eccezione di diverse configurazioni apportate )

Configurazione di Docker
Il file docker-compose.yml definisce i seguenti servizi:

### laravel.test:

Costruisce l'immagine dell'applicazione Laravel.
Espone le porte configurate per l'applicazione e Vite.
Monta il volume corrente nella directory /var/www/html del container.
Dipende dal servizio mysql.

### mysql:

Utilizza l'immagine mysql/mysql-server:8.0.
Espone la porta del database (default 3307 usata per eventuali conflitti con altri servizi di mysql già attivi ).
Configura le variabili d'ambiente per il database, inclusi nome, utente e password.
Tali impostazioni si trovano in questo formato sul file docker-compose
```bash
        mysql:
        image: 'mysql/mysql-server:8.0'
        ports:
            - '${FORWARD_DB_PORT:-3307}:3306'
        environment:
            MYSQL_ROOT_PASSWORD: '${DB_PASSWORD}'
            MYSQL_ROOT_HOST: '%'
            MYSQL_DATABASE: '${DB_DATABASE}'
            MYSQL_USER: '${DB_USERNAME}'
            MYSQL_PASSWORD: '${DB_PASSWORD}'
            MYSQL_ALLOW_EMPTY_PASSWORD: 1
```
Monta un volume per la persistenza dei dati.


## Utilizzo dell'API

L'API RESTful fornisce i seguenti endpoint per gestire gli ordini e i prodotti.

### Codici di stato API

- 200: Richiesta completata con successo
- 201: Risorsa creata con successo
- 400: Errore di validazione
- 404: Risorsa non trovata
- 500: Errore interno del server

### Endpoints per gli Ordini

1. **GET /api/orders**:  
   Recupera la lista degli ordini, con possibilità di filtro per nome e data tramite parametri di query.

   **Esempio di Risposta**:
   ```json
   [
       {
           "id": 1,
           "name": "Ordine 1",
           "description": "Descrizione dell'ordine 1",
           "order_date": "2024-09-03T12:34:56",
           "total_value": "100.00",
           "products": [
               {
                   "id": 1,
                   "name": "Prodotto A",
                   "price": "25.00",
                   "qty": 2
               },
               {
                   "id": 2,
                   "name": "Prodotto B",
                   "price": "50.00",
                   "qty": 1
               }
           ]
       }
   ]
   
2. **GET /api/orders/{id}**:  
   Recupera i dettagli di un ordine specifico, inclusi i prodotti associati.
   **Esempio di Risposta**:
   ```json
   {
    "id": 1,
    "name": "Ordine 1",
    "description": "Descrizione dell'ordine 1",
    "order_date": "2024-09-03T12:34:56",
    "total_value": "100.00",
    "products": [
        {
            "id": 1,
            "name": "Prodotto A",
            "price": "25.00",
            "qty": 2
        }
    ]
}

3. **POST /api/orders**:  
   Crea un nuovo ordine. Richiede dati di input validati tramite `StoreOrderRequest`.
   
    **Esempio di Risposta**:
   ```json
   {
    "id": 2,
    "name": "Ordine 2",
    "description": "Nuovo ordine creato",
    "order_date": "2024-09-03T13:00:00",
    "total_value": "150.00",
    "products": []
}


4. **PUT /api/orders/{id}**:  
   Modifica un ordine esistente. Richiede dati di input validati tramite `UpdateOrderRequest`.
   
    **Esempio di Risposta**:
   ```json
   {
    "id": 1,
    "name": "Ordine 1 Modificato",
    "description": "Descrizione aggiornata",
    "order_date": "2024-09-03T12:34:56",
    "total_value": "120.00",
    "products": []
    }

5. **DELETE /api/orders/{id}**:  
   Elimina un ordine esistente.

    **Esempio di Risposta**:
   ```json
   {
    "esito": true   
    }

6. **GET /api/orders/stats**:  
   Restituisce statistiche degli ordini, inclusi:
    - Totale ordini
    - Numero di ordini odierni
    - Somma totale degli importi degli ordini

   **Esempio di Risposta**:
   ```json
   {
    "totale_ordini": 50,
    "ordini_odierni": 5,
    "somma": 5000.00,
    "esito": true
    }
   ```

### Endpoints per i Prodotti

1. **GET /api/products**:  
   Recupera la lista dei prodotti, con possibilità di filtro per nome.

   **Esempio di Risposta**:
   ```json
      [
        {
            "id": 1,
            "name": "Prodotto A",
            "description": "Descrizione del prodotto A",
            "price": "25.00",
            "created_at": "2024-09-01T08:30:00",
            "updated_at": "2024-09-01T08:30:00",
            "orders": []
        }
    ]
   ```

2. **GET /api/products/{id}**:  
   Recupera i dettagli di un prodotto specifico, inclusi gli ordini associati.


**Esempio di Risposta**:
   ```json
   {
    "id": 1,
    "name": "Prodotto A",
    "description": "Descrizione del prodotto A",
    "price": "25.00",
    "created_at": "2024-09-01T08:30:00",
    "updated_at": "2024-09-01T08:30:00",
    "orders": [
        {
            "id": 1,
            "name": "Ordine 1",
            "total_value": "100.00",
            "pivot": {
                "qty": 2
            }
        }
    ]
}
   ```

3. **POST /api/products**:  
   Crea un nuovo prodotto. Richiede dati di input validati tramite `StoreProductRequest`.


**Esempio di Risposta**:
   ```json
   {
    "id": 3,
    "name": "Prodotto C",
    "description": "Nuovo prodotto creato",
    "price": "30.00",
    "created_at": "2024-09-03T13:00:00",
    "updated_at": "2024-09-03T13:00:00",
    "orders": []
}

   ```

4. **PUT /api/products/{id}**:  
   Modifica un prodotto esistente. Richiede dati di input validati tramite `UpdateProductRequest`.

   **Esempio di Risposta**:
   ```json
   {
    "id": 1,
    "name": "Prodotto A Modificato",
    "description": "Descrizione aggiornata",
    "price": "27.00",
    "created_at": "2024-09-01T08:30:00",
    "updated_at": "2024-09-03T13:30:00",
    "orders": []
    }
   ```

5. **DELETE /api/products/{id}**:  
   Elimina un prodotto esistente.

**Esempio di Risposta**:
   ```json
    {
    "esito": true
  }
```
**GET /api/products/search**:
Cerca prodotti per nome basato su una query string.

Parametri di Query:
- `q` (opzionale): Stringa di ricerca per filtrare i prodotti per nome.

    Esempio di Risposta:
   ```json
   {
    "total_count": 1,
    "items": [
        {
            "id": 1,
            "name": "Prodotto A",
            "description": "Descrizione del prodotto A",
            "price": "25.00"
        }
    ]
  }

## Dipendenze aggiuntive

- Yajra DataTables: Utilizzato per la gestione delle tabelle dati nelle API.
- Vite: Utilizzato per il build e lo sviluppo frontend.

## Validazione delle richieste

Le richieste API sono validate utilizzando Form Request personalizzate:

- `StoreOrderRequest`: Valida la creazione di nuovi ordini.
- `UpdateOrderRequest`: Valida l'aggiornamento degli ordini esistenti.
- `StoreProductRequest`: Valida la creazione di nuovi prodotti.
- `UpdateProductRequest`: Valida l'aggiornamento dei prodotti esistenti.

Queste classi definiscono regole di validazione e messaggi di errore personalizzati.

## Service Providers

L'applicazione utilizza i seguenti service provider personalizzati:

- `OrderServiceProvider`: Gestisce la logica di business relativa agli ordini.
- `ProductServiceProvider`: Gestisce la logica di business relativa ai prodotti.

## Test
Per eseguire i test, utilizzare il seguente comando:
```bash
vendor/bin/phpunit
```
Assicurarsi che tutti i test siano stati eseguiti con successo per garantire la qualità del codice.


## Licenza
Questo progetto è concesso in licenza sotto la Licenza MIT.
