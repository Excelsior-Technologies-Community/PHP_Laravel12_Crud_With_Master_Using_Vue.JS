# PHP_Laravel12_Crud_With_Master_Using_Vue.JS

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel">
  <img src="https://img.shields.io/badge/Vue.js-3.x-42b883?style=for-the-badge&logo=vue.js">
  <img src="https://img.shields.io/badge/Inertia.js-SPA-blueviolet?style=for-the-badge">
  <img src="https://img.shields.io/badge/Breeze-Auth-success?style=for-the-badge">
</p>

---

##  Overview

This project demonstrates a **complete Master CRUD (Category & Product)** system built with:

- **Laravel 12**
- **Breeze Authentication**
- **Inertia.js**
- **Vue 3**

Category acts as **Master** and Product as **Child** (One-to-Many relationship).

---

##  Features

- Laravel 12 backend
- Breeze authentication
- Vue 3 + Inertia.js SPA
- Category CRUD
- Product CRUD with category dropdown
- One-to-many relationship
- Tailwind CSS UI
- Clean, scalable project structure

---

##  Folder Structure

```
app/
├── Http/Controllers/
│   ├── CategoryController.php
│   └── ProductController.php
├── Models/
│   ├── Category.php
│   └── Product.php

database/
└── migrations/
    ├── xxxx_create_categories_table.php
    └── xxxx_create_products_table.php

resources/
├── js/
│   ├── app.js
│   ├── bootstrap.js
│   └── Pages/
│       ├── Category/
│       │   ├── Index.vue
│       │   ├── Create.vue
│       │   └── Edit.vue
│       └── Product/
│           ├── Index.vue
│           ├── Create.vue
│           └── Edit.vue
└── views/
    └── app.blade.php

routes/web.php
.env
README.md
```

---

##  STEP 1: Install Laravel 12

```bash
composer create-project laravel/laravel laravel12-crud
cd laravel12-crud
```

---

##  STEP 2: Database Configuration (.env)

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=crud
DB_USERNAME=root
DB_PASSWORD=
```

Create database **crud** in phpMyAdmin.

---

##  STEP 3: Install Breeze (Vue + Inertia)

```bash
composer require laravel/breeze --dev

php artisan breeze:install
```

Select **Vue with Inertia**, then run:

```bash
npm install

php artisan migrate

npm run dev

php artisan serve
```

---

##  STEP 4: Create Models & Migrations

### Category

```bash
php artisan make:model Category -m
```

**Migration**
```php
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->timestamps();
});
```

**Model**
```php
class Category extends Model
{
    protected $fillable = ['name'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
```

---

### Product

```bash
php artisan make:model Product -m
```

**Migration**
```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('details');
    $table->decimal('price',10,2);
    $table->foreignId('category_id')->constrained()->cascadeOnDelete();
    $table->timestamps();
});
```

**Model**
```php
class Product extends Model
{
    protected $fillable = ['name','details','price','category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
```

---

##  STEP 5: Controllers

### CategoryController
```php
php artisan make:controller CategoryController
```

```php
class CategoryController extends Controller
{
    public function index()
    {
        return Inertia::render('Category/Index', [
            'categories' => Category::latest()->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Category/Create');
    }

    public function store(Request $request)
    {
        $request->validate(['name'=>'required']);
        Category::create($request->all());
        return redirect()->route('category.index');
    }

    public function edit(Category $category)
    {
        return Inertia::render('Category/Edit', ['category'=>$category]);
    }

    public function update(Request $request, Category $category)
    {
        $category->update($request->all());
        return redirect()->route('category.index');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back();
    }
}
```

---

### ProductController
```php
php artisan make:controller ProductController
```

```php
class ProductController extends Controller
{
    public function index()
    {
        return Inertia::render('Product/Index', [
            'products' => Product::with('category')->latest()->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Product/Create', [
            'categories' => Category::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'details'=>'required',
            'price'=>'required|numeric',
            'category_id'=>'required'
        ]);

        Product::create($request->all());
        return redirect()->route('product.index');
    }

    public function edit(Product $product)
    {
        return Inertia::render('Product/Edit', [
            'product'=>$product,
            'categories'=>Category::all()
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->all());
        return redirect()->route('product.index');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back();
    }
}
```

---

##  STEP 6: Routes (routes/web.php)

```php
Route::middleware(['auth'])->group(function () {

    Route::get('/category',[CategoryController::class,'index'])->name('category.index');
    Route::get('/category/create',[CategoryController::class,'create'])->name('category.create');
    Route::post('/category',[CategoryController::class,'store'])->name('category.store');
    Route::get('/category/{category}/edit',[CategoryController::class,'edit'])->name('category.edit');
    Route::put('/category/{category}',[CategoryController::class,'update'])->name('category.update');
    Route::delete('/category/{category}',[CategoryController::class,'destroy'])->name('category.destroy');

    Route::get('/product',[ProductController::class,'index'])->name('product.index');
    Route::get('/product/create',[ProductController::class,'create'])->name('product.create');
    Route::post('/product',[ProductController::class,'store'])->name('product.store');
    Route::get('/product/{product}/edit',[ProductController::class,'edit'])->name('product.edit');
    Route::put('/product/{product}',[ProductController::class,'update'])->name('product.update');
    Route::delete('/product/{product}',[ProductController::class,'destroy'])->name('product.destroy');
});
```

---

##  STEP 7: Inertia Setup

### resources/js/app.js
```js
import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
  title: title => `${title} - ${appName}`,
  resolve: name =>
    resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
  setup({ el, App, props, plugin }) {
    return createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .mount(el);
  },
});
```

---

### resources/views/app.blade.php
```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    @routes
    @vite(['resources/js/app.js'])
    @inertiaHead
</head>
<body class="font-sans antialiased">
    @inertia
</body>
</html>
```

---


# STEP 8:  VUE FILES 

---
CATEGORY FILES:-

##  resources/js/Pages/Category/Index.vue

```vue
<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  categories: Array
})
</script>

<template>
  <div>
    <h1>Categories</h1>
    <Link href="/category/create">Add Category</Link>

    <table>
      <tr v-for="cat in categories" :key="cat.id">
        <td>{{ cat.name }}</td>
        <td>
          <Link :href="`/category/${cat.id}/edit`">Edit</Link>
        </td>
      </tr>
    </table>
  </div>
</template>
```

---

## resources/js/Pages/Category/Create.vue

```vue
<script setup>
import { useForm, Link } from '@inertiajs/vue3'

const form = useForm({ name: '' })

const submit = () => {
  form.post('/category')
}
</script>

<template>
  <form @submit.prevent="submit">
    <input v-model="form.name" placeholder="Category name" />
    <button>Create</button>
    <Link href="/category">Back</Link>
  </form>
</template>
```

---

##  resources/js/Pages/Category/Edit.vue

```vue
<script setup>
import { useForm, Link } from '@inertiajs/vue3'

const props = defineProps({
  category: Object
})

const form = useForm({
  name: props.category.name
})

const update = () => {
  form.put(`/category/${props.category.id}`)
}
</script>

<template>
  <form @submit.prevent="update">
    <input v-model="form.name" />
    <button>Update</button>
    <Link href="/category">Back</Link>
  </form>
</template>
```

---

PRODUCTS FILS:-

##   resources/js/Pages/Product/Index.vue

```vue
<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  products: Array
})
</script>

<template>
  <div>
    <h1>Products</h1>
    <Link href="/product/create">Add Product</Link>

    <table>
      <tr v-for="product in products" :key="product.id">
        <td>{{ product.name }}</td>
        <td>{{ product.category.name }}</td>
        <td>
          <Link :href="`/product/${product.id}/edit`">Edit</Link>
        </td>
      </tr>
    </table>
  </div>
</template>
```

---

##  resources/js/Pages/Product/Create.vue

```vue
<script setup>
import { useForm, Link } from '@inertiajs/vue3'

defineProps({
  categories: Array
})

const form = useForm({
  name: '',
  details: '',
  price: '',
  category_id: ''
})

const submit = () => {
  form.post('/product')
}
</script>

<template>
  <form @submit.prevent="submit">
    <input v-model="form.name" placeholder="Product name" />
    <textarea v-model="form.details"></textarea>
    <input v-model="form.price" type="number" />
    <select v-model="form.category_id">
      <option value="">Select Category</option>
      <option v-for="cat in categories" :value="cat.id">
        {{ cat.name }}
      </option>
    </select>

    <button>Create</button>
    <Link href="/product">Back</Link>
  </form>
</template>
```

---

##  resources/js/Pages/Product/Edit.vue

```vue
<script setup>
import { useForm, Link } from '@inertiajs/vue3'

const props = defineProps({
  product: Object,
  categories: Array
})

const form = useForm({
  name: props.product.name,
  details: props.product.details,
  price: props.product.price,
  category_id: props.product.category_id
})

const update = () => {
  form.put(`/product/${props.product.id}`)
}
</script>

<template>
  <form @submit.prevent="update">
    <input v-model="form.name" />
    <textarea v-model="form.details"></textarea>
    <input v-model="form.price" />
    <select v-model="form.category_id">
      <option v-for="cat in categories" :value="cat.id">
        {{ cat.name }}
      </option>
    </select>

    <button>Update</button>
    <Link href="/product">Back</Link>
  </form>
</template>
```


---

##  Final URLs

```
http://127.0.0.1:8000/category
```
INDEX PAGE:-

<img width="1160" height="428" alt="Screenshot 2025-12-17 114548" src="https://github.com/user-attachments/assets/e805da83-871b-461b-bdd0-58432336d784" />

CREATE PAGE:-

<img width="653" height="384" alt="Screenshot 2025-12-17 114555" src="https://github.com/user-attachments/assets/79b84665-b5c0-4b17-87a9-5548d7547e65" />

EDIT PAGE:-

<img width="614" height="388" alt="Screenshot 2025-12-17 114603" src="https://github.com/user-attachments/assets/b5257ad4-52c6-44b9-a9f9-64974acc3b72" />




```
http://127.0.0.1:8000/product
```
INDEX PAGE:-

<img width="1310" height="475" alt="Screenshot 2025-12-17 114619" src="https://github.com/user-attachments/assets/b6aa3b4b-fc1f-41ff-b3d2-f31dae32cc56" />

CREATE PAGE:-

<img width="783" height="654" alt="Screenshot 2025-12-17 114629" src="https://github.com/user-attachments/assets/69111bc6-063f-4caa-98c1-19343069ee85" />

EDIT PAGE:-

<img width="694" height="686" alt="Screenshot 2025-12-17 114638" src="https://github.com/user-attachments/assets/ef81c401-0af4-4e9c-b391-b00fc80a33dc" />


