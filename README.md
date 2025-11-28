# 🚀 Artisan GUI 

---
**A modern, secure, and beautifully designed web interface for running Laravel Artisan commands.**

> A sleek dashboard that transforms `php artisan` into a safe, team-friendly, role-based graphical interface — without exposing server access.

---

````markdown




## ✨ Key Features

### 🎨 Modern UI/UX
- Fully responsive design  
- Smooth **dark & light mode**  
- Clean TailwindCSS-powered layout  
- Real-time command output streaming  

### 🔒 Enterprise Security
- Full **command whitelisting**
- **RBAC role-based permissions**
- CSRF protection  
- Complete audit trail for all executed commands  

### ⚡ Real-Time Execution
- Live command output  
- Async execution via AJAX  
- Execution statuses (running / success / failed)  
- Detailed error handling  

### 📜 Audit Logging
- Complete command history  
- Searchable & filterable logs  
- User-based trails  
- Downloadable log files  

### 🌍 Internationalization (i18n)
- Full multi-language support  
- English as the base language  
- Auto-translation support (Google/DeepL)  
- Easy language expansion  

### 🧩 Modular Laravel Package
- Publishable config, views, migrations, and lang files  
- Customizable and extendable  
- Auto-discovered Service Provider  

---

## 📦 Requirements

| Component   | Version |
| ----------- | ------- |
| PHP         | 8.2+    |
| Laravel     | 11.x+   |
| Database    | Any supported by Laravel |

---

## 🛠 Installation

### 1️⃣ Install via Composer
```bash
composer require sabiowebcom/artisan-gui-package
````

### 2️⃣ Publish Package Assets

```bash
php artisan vendor:publish --tag=artisan-gui-config
php artisan vendor:publish --tag=artisan-gui-migrations
php artisan vendor:publish --tag=artisan-gui-views
php artisan vendor:publish --tag=artisan-gui-lang
```

### 3️⃣ Run Migrations

```bash
php artisan migrate
```

---

## ⚙️ Configuration

### `.env` Variables

```env
ARTISAN_GUI_PREFIX=artisan-gui
ARTISAN_GUI_LOCALE=en

ARTISAN_GUI_AUTO_TRANSLATE=false
ARTISAN_GUI_TRANSLATION_PROVIDER=google
ARTISAN_GUI_TRANSLATION_API_KEY=your-api-key
ARTISAN_GUI_TARGET_LANGUAGES=fa,ar,es,fr,de
```

### `config/artisan-gui.php`

Fully customizable settings:

* route prefix
* allowed commands
* allowed user roles
* max execution time
* log storage path
* i18n settings

---

## 🔧 Usage

### Access the Dashboard

```
http://your-app.test/artisan-gui
```

### Main Pages

* `/artisan-gui` — Dashboard
* `/artisan-gui/run` — Execute commands
* `/artisan-gui/catalog` — Browse commands
* `/artisan-gui/history` — Execution history
* `/artisan-gui/about` — Package info

### Running Commands

1. Open **Run Command**
2. Select an Artisan command
3. Enter parameters (optional)
4. Run
5. Watch real-time output

---

## 📡 API Endpoints

### Execute Command

```http
POST /artisan-gui/api/execute
```

### List Commands

```http
GET /artisan-gui/api/commands
```

### Run Details

```http
GET /artisan-gui/api/runs/{id}
```

### Download Log

```http
GET /artisan-gui/api/runs/{id}/log
```

---

## 🌍 Localization

### Set Locale

```env
ARTISAN_GUI_LOCALE=fa
```

Or:

```
/artisan-gui?lang=fa
```

### Auto-Translate

```bash
php artisan artisan-gui:translate
```

---

## 🎨 Dark Mode

* Light & Dark themes
* Auto-detect system theme
* Smooth transitions
* Saves user preference

---

## 🔒 Security

* Command whitelisting
* Role-based access control
* CSRF protection
* Safe validation & exception handling

---

## 🎨 Customization

### Override Views

```bash
php artisan vendor:publish --tag=artisan-gui-views
```

### Override Lang Files

```bash
php artisan vendor:publish --tag=artisan-gui-lang
```

### Change Route Prefix

```env
ARTISAN_GUI_PREFIX=admin/artisan
```

---

## 🧪 Testing

```bash
composer test
```

or:

```bash
./vendor/bin/phpunit
```

---

## 📚 Project Structure

```
artisan-gui-package/
├── config/
├── database/
├── resources/
│   ├── lang/
│   └── views/
├── routes/
├── src/
└── tests/
```

---

## 🤝 Contributing

1. Fork
2. Create branch
3. Follow PSR-12
4. Write tests
5. Submit PR

---

## 📝 Changelog

See `CHANGELOG.md`.

---

## 🪪 License

MIT License

---

## ⭐ Support

* Star the repo
* Report issues
* Suggest features
* Improve docs

---

🙏 Acknowledgments
Built with ❤️ by Sabiowebcom Team
Author: Ramezanzadeh
Team: Sabioweb
Site : Sabioweb.com

---


