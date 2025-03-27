## Konfiguration Red Provider

Die Integration mit dem Red Provider benötigt folgende Umgebungsvariablen in Ihrer `.env` Datei:

| Variable                    | Beschreibung                                  | Beispiel                           |
|----------------------------|----------------------------------------------|-----------------------------------|
| `RED_PROVIDER_URL`         | Basis-URL der Red Provider API               | `https://localhost:3000/api/v1/`  |
| `RED_PROVIDER_CLIENT_ID`   | Client ID für die API-Authentifizierung     | `<your-client-id>`               |
| `RED_PROVIDER_CLIENT_SECRET`| Client Secret für die API-Authentifizierung | `<your-client-secret>`           |
| `RED_PROVIDER_USE_MOCK`    | Mock-Service für Testzwecke aktivieren      | `false`                          |
| `RED_PROVIDER_CERT_PATH`   | Pfad zum SSL-Zertifikat                     | `routes/ssl_cert.pem`            |

### Schnellstart

1. Kopieren Sie diese Variablen in Ihre `.env` Datei:
```env
RED_PROVIDER_URL=https://localhost:3000/api/v1/
RED_PROVIDER_CLIENT_ID=<your-client-id>
RED_PROVIDER_CLIENT_SECRET=<your-client-secret>
RED_PROVIDER_USE_MOCK=false
RED_PROVIDER_CERT_PATH=routes/ssl_cert.pem
```

2. Ersetzen Sie die Platzhalter mit Ihren tatsächlichen Zugangsdaten
3. Stellen Sie sicher, dass das SSL-Zertifikat am angegebenen Pfad existiert

### Entwicklungsmodus

Für lokale Entwicklung
