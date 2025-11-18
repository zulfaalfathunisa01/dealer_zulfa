# Class Diagram for Dealer Motor Zulfa Website

This document presents the class diagram for the Dealer Motor Zulfa website, based on the database schema and PHP code structure. It identifies the main classes (entities), their attributes, and relationships.

## Overview
The system is built using PHP with MySQL database. The classes represent the main entities in the system. Relationships include associations, aggregations, and compositions.

## Main Classes

### 1. Pengguna (User)
Represents registered users/customers.
- **Attributes:**
  - id_pengguna (int, primary key)
  - nama_pengguna (string)
  - email (string)
  - password (string, hashed)
  - no_hp (string)
  - alamat (string)
  - foto (string, optional)
- **Methods:**
  - login()
  - register()
  - updateProfile()
  - viewHistory()

### 2. Admin
Represents administrators.
- **Attributes:**
  - id_admin (int, primary key)
  - email (string)
  - password (string, hashed)
- **Methods:**
  - login()
  - manageProducts()
  - manageUsers()
  - manageTransactions()
  - viewReports()

### 3. Produk (Product)
Represents motorcycle products.
- **Attributes:**
  - id_produk (int, primary key)
  - nama_produk (string)
  - merk_id (int, foreign key)
  - harga (decimal)
  - deskripsi (text)
  - stock (int)
  - kategori (string)
  - photo (string)
- **Methods:**
  - getDetails()
  - updateStock()
  - isAvailable()

### 4. Merk (Brand)
Represents motorcycle brands.
- **Attributes:**
  - id_merk (int, primary key)
  - nama_merk (string)
- **Methods:**
  - getProducts()

### 5. Transaksi (Transaction)
Represents orders/transactions.
- **Attributes:**
  - id_transaksi (int, primary key)
  - pengguna_id (int, foreign key)
  - admin_id (int, foreign key)
  - tanggal_transaksi (datetime)
  - total_harga (decimal)
  - status (string: proses, kirim, selesai, batal)
  - catatan_batal (text, optional)
- **Methods:**
  - create()
  - updateStatus()
  - cancel()
  - getDetails()

### 6. TransaksiDetail (TransactionDetail)
Represents individual items in a transaction.
- **Attributes:**
  - id (int, primary key)
  - transaksi_id (int, foreign key)
  - produk_id (int, foreign key)
  - jumlah (int)
  - harga (decimal)
- **Methods:**
  - calculateSubtotal()

### 7. Keranjang (Cart)
Represents shopping cart items.
- **Attributes:**
  - id_keranjang (int, primary key)
  - id_pengguna (int, foreign key)
  - id_produk (int, foreign key)
  - qty (int)
- **Methods:**
  - addItem()
  - updateQty()
  - removeItem()
  - getTotal()

### 8. Wishlist
Represents user's wishlisted products.
- **Attributes:**
  - id_wishlist (int, primary key)
  - id_pengguna (int, foreign key)
  - id_produk (int, foreign key)
  - tanggal_ditambahkan (datetime)
- **Methods:**
  - addToWishlist()
  - removeFromWishlist()

## Relationships

- **Pengguna** 1..* ---- 1..* **Keranjang** (One user can have many cart items)
- **Pengguna** 1..* ---- 1..* **Wishlist** (One user can have many wishlist items)
- **Pengguna** 1..* ---- 1..* **Transaksi** (One user can have many transactions)
- **Admin** 1..* ---- 1..* **Transaksi** (One admin can manage many transactions)
- **Merk** 1..* ---- 1..* **Produk** (One brand can have many products)
- **Produk** 1..* ---- 1..* **Keranjang** (One product can be in many carts)
- **Produk** 1..* ---- 1..* **Wishlist** (One product can be wishlisted by many users)
- **Produk** 1..* ---- 1..* **TransaksiDetail** (One product can be in many transaction details)
- **Transaksi** 1..* ---- 1..* **TransaksiDetail** (One transaction can have many details)

## Mermaid Class Diagram

```mermaid
classDiagram
    class Pengguna {
        +int id_pengguna
        +string nama_pengguna
        +string email
        +string password
        +string no_hp
        +string alamat
        +string foto
        +login()
        +register()
        +updateProfile()
        +viewHistory()
    }

    class Admin {
        +int id_admin
        +string email
        +string password
        +login()
        +manageProducts()
        +manageUsers()
        +manageTransactions()
        +viewReports()
    }

    class Produk {
        +int id_produk
        +string nama_produk
        +int merk_id
        +decimal harga
        +text deskripsi
        +int stock
        +string kategori
        +string photo
        +getDetails()
        +updateStock()
        +isAvailable()
    }

    class Merk {
        +int id_merk
        +string nama_merk
        +getProducts()
    }

    class Transaksi {
        +int id_transaksi
        +int pengguna_id
        +int admin_id
        +datetime tanggal_transaksi
        +decimal total_harga
        +string status
        +text catatan_batal
        +create()
        +updateStatus()
        +cancel()
        +getDetails()
    }

    class TransaksiDetail {
        +int id
        +int transaksi_id
        +int produk_id
        +int jumlah
        +decimal harga
        +calculateSubtotal()
    }

    class Keranjang {
        +int id_keranjang
        +int id_pengguna
        +int id_produk
        +int qty
        +addItem()
        +updateQty()
        +removeItem()
        +getTotal()
    }

    class Wishlist {
        +int id_wishlist
        +int id_pengguna
        +int id_produk
        +datetime tanggal_ditambahkan
        +addToWishlist()
        +removeFromWishlist()
    }

    Pengguna ||--o{ Keranjang : has
    Pengguna ||--o{ Wishlist : has
    Pengguna ||--o{ Transaksi : places
    Admin ||--o{ Transaksi : manages
    Merk ||--o{ Produk : contains
    Produk ||--o{ Keranjang : in
    Produk ||--o{ Wishlist : wishlisted
    Produk ||--o{ TransaksiDetail : ordered
    Transaksi ||--o{ TransaksiDetail : contains
```

## Notes
- This diagram represents the static structure of the system.
- In the actual PHP code, these are implemented as database tables with procedural functions.
- Relationships are based on foreign keys in the database schema.
- Methods are inferred from the code functionality (e.g., login, addItem).

This class diagram provides a clear overview of the system's structure and can be used for further development or documentation.
