## Product Wholesale Management 

This project provides an interface to manage selling products in wholesale while still providing the option of buying a single product as well. The main features of this application are:

- Blogging
- User management
- Image management
- Internationalization (Currency / Language)
- Categorisation / Tagging of products
- Brands Management
- Shop Management
- Products Management (HERE)
- Cart System
- Paypal Payment System
- Product promo
- Add country_id to users
- Add language_id to users
- Price usd converter
- Set job to clean deleted models
______________________________
- Newsletter Management
- Wishlist 
- Ratings
- CSV/Excel Export
- Catalogue Creation
- SEO Optimization

The application is developed in a TDD way to ensure that our code is reliable

## Features implemented already
- Blogging with image,published scope, categorisable (90%) - policies (user dependent)
- User management(50%)  with Roles (Admin, Wholesaler, Client) 
- Image management(50%) 
- Internationalization (Currency / Language / Country)
- Categorisation / Tagging of products
- Brands Management(50%)
- Shop Management(50%)
- Products Management(50%)
- Cart System(50%)
- Translatable
- Sluggable
- Product searchable/filterable

## Interesting Features
- Base Controller

## Features in progress
- Front end layout - Product
- Front end layout - Cart
- Front end layout - Checkout

## Improvements
- Abstract Internationalization into a package

## The architecture
- The admin front end interface will be developed using Vue JS.
- The admin back end will be developed using Laravel Rest API taking advantage of Laravel Passport
- The client front end interface will be developed using Blade Templating System
- The client back end interface will be developed using the traditional Request to Response Cycle

An attempt to provide at least 80%  test coverage of our code

## Technologies used
Laravel
Vue JS
SASS
Bootstrap 4

## The inspiration
I recently travelled to China Yiwu International Trade City to explore the commodities market. This project is aimed to solve several issues that I faced, or saw the shop owners face when trying to do business in an international environment. Some are the issues were:
- No website for their products
- Difficulty in communication since the spoken language of the shop owners was mandarin
- No product catalogue. Pictures were usually shared by WeChat
- All quotations and orders had to be done by hand
- No proper tracking of their orders
- No clear information about the market online

I am also using this project to demonstrate my skills as a Laravel/VueJS/Sass Developer as well as deploying the site on a live server.
