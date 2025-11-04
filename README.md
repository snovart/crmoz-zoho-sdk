# CRMoz Zoho SDK — Local Sync Commands

This project provides a set of Laravel Artisan commands to **synchronize Zoho CRM data (Accounts, Contacts, and Leads)** into a local database using the **CRMoz Zoho SDK**.

Each command performs:
- A full **page-by-page sync** from Zoho CRM to local DB.
- Automatic **table creation** via the SDK if the table does not yet exist.
- Safe handling of paginated results and empty collections.

---

## Project Structure

```
app/
├── Console/
│   └── Commands/
│       ├── SyncAccountsCommand.php
│       ├── SyncContactsCommand.php
│       └── SyncLeadsCommand.php
├── Models/
│   ├── Account.php
│   ├── Contact.php
│   └── Lead.php
└── ModelsZoho/
    ├── AccountZoho.php
    ├── ContactZoho.php
    └── LeadZoho.php
```

---

## Models Overview

### 1. `App\Models\Account`, `Contact`, `Lead`
Local Eloquent models representing the database tables:
```php
protected $table = 'accounts'; // or 'contacts', 'leads'
protected $guarded = [];
public $incrementing = false;
```

These models are simple Eloquent wrappers around local DB tables.

---

### 2. `App\ModelsZoho\AccountZoho`, `ContactZoho`, `LeadZoho`
Zoho SDK wrappers extending `ZohoCrmSDK\ModelsZoho\*ZohoModel`.

Example:
```php
namespace App\ModelsZoho;

use App\Models\Contact;
use ZohoCrmSDK\ModelsZoho\ContactZohoModel;

class ContactZoho extends ContactZohoModel
{
    protected $modelDB = Contact::class;
}
```

Each `*Zoho` model defines a `$modelDB` property pointing to the corresponding local model.  
This allows SDK methods like `all($page)` and `saveToDB()` to work seamlessly with your local database.

---

## Commands Overview

### 1. Sync Zoho Accounts → Local DB

**File:** `app/Console/Commands/SyncAccountsCommand.php`

**Command:**
```bash
php artisan accounts:sync
```

**Description:**
- Checks if `accounts` table exists.
- If not — automatically creates it via:
  ```bash
  php artisan zoho-crm-sdk:sync-records --model=AccountZoho
  ```
- Then fetches all pages from Zoho and saves each page to DB.

---

### 2. Sync Zoho Contacts → Local DB

**File:** `app/Console/Commands/SyncContactsCommand.php`

**Command:**
```bash
php artisan contacts:sync
```

**Description:**
- Checks if `contacts` table exists.
- If missing — creates it via SDK.
- Then iterates through all Zoho pages, saving records to DB.

---

### 3. Sync Zoho Leads → Local DB

**File:** `app/Console/Commands/SyncLeadsCommand.php`

**Command:**
```bash
php artisan leads:sync
```

**Description:**
- Checks if `leads` table exists.
- If missing — creates it via SDK.
- Then performs page-by-page synchronization.

---

## Sync Flow

Each command performs the same 5-step logic:

1. **Check Table**
   - Uses `Schema::hasTable()` to verify local DB structure.
2. **Auto-Create Table**
   - Runs `zoho-crm-sdk:sync-records` with model parameter.
3. **Fetch Data Page-by-Page**
   - Calls `ModelZoho::all($page)` for each page.
4. **Save Data**
   - Uses `$collection->saveToDB()` to insert/update records.
5. **Stop When Empty**
   - Stops when Zoho returns an empty collection.

---

## Helper Methods

Each command includes two internal helpers:

```php
private function isEmptySdkCollection($collection): bool
private function countSdkCollection($collection): int
```

These ensure compatibility with different SDK collection types.

---

## Usage Summary

| Command | Purpose | Table | SDK Model |
|----------|----------|--------|------------|
| `php artisan accounts:sync` | Sync Zoho Accounts | `accounts` | `AccountZoho` |
| `php artisan contacts:sync` | Sync Zoho Contacts | `contacts` | `ContactZoho` |
| `php artisan leads:sync` | Sync Zoho Leads | `leads` | `LeadZoho` |

---

## Requirements

- Laravel 10+
- Installed **CRMoz Zoho SDK**
- Configured Zoho CRM credentials in `.env`
- Database connection properly set in `config/database.php`

---

## Example Output

```bash
> php artisan contacts:sync

Checking local DB structure...
Table "contacts" not found. Creating via SDK...
Zoho SDK: creating table...
Table "contacts" is ready.
Starting full Contacts sync (page-by-page)...
Fetching page 1...
Saved 200 contacts from page 1.
Fetching page 2...
Saved 195 contacts from page 2.
No more pages. Finishing...
Done. Total saved: 395
```

---

## Notes

- You can safely rerun sync commands — records are updated by Zoho ID.
- Table creation happens only once.
- Pagination is handled internally by SDK.
- All logging output is shown in console.

---

**Author:** snovart 
**Version:** 1.0.0  
**License:** MIT
