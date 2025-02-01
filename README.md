# Laravel Project Setup Guide

This document outlines the steps necessary to get this Laravel application up and running on your local development environment.

## Prerequisites

Before you begin, ensure you have the following installed on your machine:
- PHP (>=8.0)
- Composer
- A web server like Apache or Nginx
- MySQL

## Installation

Follow these steps to install the application:

1. **Clone the Repository**

   ```bash
   git clone https://github.com/RanaGulraiz045/laravel-coding-challenge.git
   cd laravel-coding-challenge

2. **Install Packages**

   Run following command in project terminal
   ```bash
   composer install

3. **Setup Env**

   ```bash
   cp .env.example .env   

4. **Generate Application Key**

   ```bash
   php artisan key:generate

5. **Setup Database**

   Update the Database Credentials before runing the following commands
   ```bash
   php artisan migrate
   php artisan db:seed

6. **Run Project**

   ```bash
   php artisan serve

We can check the API documentation using on localhost:Port/api/documentation