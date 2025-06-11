# API Endpoints Documentation

This document outlines the API endpoints, their purposes, required headers, and body data.

## 1. User Management Endpoints

### POST /api/users/register
* **Purpose**: Registers a new user in the system.
* **Method**: `POST`
* **Required Headers**:
    * `Content-Type`: `application/json`

* **Body Data**: `JSON`
    * `name`: (string) - User's full name.
        * **Validation**: Required, string, maximum 255 characters.

    * `email`: (string) - User's unique email address.
        * **Validation**: Required, string, valid email format, must be unique in the 'users' table.

    * `password`: (string) - User's password.
        * **Validation**: Required, string, minimum 8 characters.

### POST /api/users/login
* **Purpose**: Login a user in the system.
* **Method**: `POST`
* **Required Headers**:
    * `Content-Type`: `application/json`

* **Body Data**: `JSON`
    * `email`: (string) - User's unique email address.
        * **Validation**: Required, string, valid email format, must exist in the 'users' table.

    * `password`: (string) - User's password.
        * **Validation**: Required, string, minimum 8 characters.

### GET /api/admin/users - `Admin Only`
* **Purpose**: List all users
* **Method**: `GET`
* **Requried Headers**: 
    * `Authorization: Bearer {token}`
* **Body Data**: `None`

### GET /api/admin/users/{id} - `Admin Only`
* **Purpose**: Get a single user
* **Method**: `GET`
* **Requried Headers**: 
    * `Authorization: Bearer {token}`
* **Body Data**: `None. The user ID is passed as a path parameter.`

### PUT /api/admin/users/{id} 
* **Purpose**: Update a user information
* **Method**: `PUT`
* **Requried Headers**: 
    * `Content-Type: application/json`
    * `Authorization: Bearer {token}`
* **Body Data**: 
    * `{  
  /* any user fields to update */  
}  `
  
### DELETE /api/admin/users/{id}  
* **Method**: `DELETE`
* **Requried Headers**: 
    * `Authorization: Bearer {token}`
* **Body Data**: `None. The user ID is passed as a path parameter.`


## 2. Book Management Endpoints*

### GET /api/books?limit={integer} - `Public`
* **Purpose**: List all books
* **Method**: `GET`
* **Required Headers**: None
* **Body Data**: None

### GET /api/books/{id} - `Public`
* **Purpose**: Get a single book with the given id.
* **Method**: `GET`
* **Required Headers**: None
* **Body Data**: None

### POST /api/admin/books - `Admin only`
* **Purpose**: Add a books to the database
* **Method**: `POST`
* **Required Headers**: `Content-Type: application-json, Authorization: Bearer {token}`
* **Body Data**: `
{  
  "title": "string (required)",  
  "author": "string (required)",  
  "published_year": "integer (required)",  
  "description": "string (required)",  
  "genre": "string (required)",  
  "image_data": "string (required, Base64-encoded image)",  
  "stock": "integer (required)",  
  "price": "numeric (required)"  
}  
`

### PUT /api/admin/books - `Admin Only`
* **Purpose**: Update a books information
* **Method**: `POST`
* **Required Headers**: `Content-Type: application-json, Authorization: Bearer {token}`
* **Body Data**: `
{  
  "title": "string (optional)",  
  "author": "string (optional)",  
  "published_year": "integer (optional)",  
  "description": "string (optional)",  
  "genre": "string (optional)",  
  "image_data": "string (optional, Base64-encoded image)",  
  "stock": "integer (optional)",  
  "price": "numeric (optional)"  
} 
`

### DELETE /api/admin/books - `Admin Only`
* **Purpose**: Delete a book
* **Method**: `DELETE`
* **Required Header**: `Authorization: Bearer {token}`
* **Body Data**: None

## 3. Order Management Endpoints (Implied CRUD Interface)

### POST /api/admin/orders - `Valid User`
* **Purpose**: Creates a new order.
* **Method**: `POST`
* **Required Headers**: `Content-Type: application-json, Authorization: Bearer {token}`
* **Body Data**:
   `{  
  "total_price": "numeric (required)",  
  "status": "string (required: 'pending', 'completed', 'canceled')",  
  "order_items": [  
    {  
      "book_id": "string (required, must exist in books)",  
      "quantity": "integer (required, min: 1)",  
      "price": "numeric (required, min: 0)"  
    }  
  ]  
} ` 

### GET /api/admin/orders - `Admin Only`
* **Purpose**: Retrieves a list of all orders.
* **Method**: `GET`
* **Required Headers**: `Authorization: Bearer {token}`
* **Body Data**: None.

### GET /admin/orders/{id} - `Admin Only`
* **Purpose**: Retrieves details of a specific order by its ID.
* **Method**: `GET`
* **Required Headers**: `Authorization: Bearer {token}`.
* **Body Data**: None. The order ID is passed as a path parameter.

### PUT /admin/orders/{id} - `Admin Only`
* **Purpose**: Updates an existing order.
* **Method**: `PUT`
* **Required Headers**:`Content-Type: application-json, Authorization: Bearer {token}`
* **Body Data**:
   ` {  
  "total_price": "numeric (optional)",  
  "status": "string (optional: 'pending', 'completed', 'canceled')"  
} `

### DELETE /admin/orders/{id}
* **Purpose**: Deletes a specific order by its ID.
* **Method**: `DELETE`
* **Required Headers**:
   * `Authorization: Bearer {token}`
* **Body Data**: None. The order ID is passed as a path parameter.