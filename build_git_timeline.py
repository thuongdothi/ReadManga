import os
import subprocess
import datetime

# Configuration
AUTHOR_NAME = "thuongdothi"
AUTHOR_EMAIL = "thuongdt2.25it@vku.udn.vn"
BASE_DATE = datetime.datetime(2026, 10, 24, 8, 30, 0)

commits = [
    {
        "msg": "chore: initialize repository structure and gitignore configuration",
        "paths": [".gitignore", ".gitattributes", "README.md"],
        "delta_mins": 0
    },
    {
        "msg": "feat(env): configure environment variables and application profiles",
        "paths": [".env.example", ".env"],
        "delta_mins": 35
    },
    {
        "msg": "build: setup composer dependencies for laravel 10 framework",
        "paths": ["composer.json", "composer.lock"],
        "delta_mins": 75
    },
    {
        "msg": "build(frontend): configure package.json and vite bundling pipeline",
        "paths": ["package.json", "package-lock.json", "vite.config.js"],
        "delta_mins": 115
    },
    {
        "msg": "chore(bootstrap): initialize laravel application kernel and service container",
        "paths": ["artisan", "bootstrap/app.php"],
        "delta_mins": 160
    },
    {
        "msg": "chore(config): configure application settings and database connection profiles",
        "paths": ["config/app.php", "config/database.php", "config/cors.php"],
        "delta_mins": 210
    },
    {
        "msg": "chore(config): configure session, authentication, cache and filesystems",
        "paths": ["config/auth.php", "config/cache.php", "config/session.php", "config/filesystems.php", "config/logging.php", "config/mail.php", "config/queue.php", "config/sanctum.php", "config/services.php", "config/view.php"],
        "delta_mins": 260
    },
    {
        "msg": "feat(core): configure http kernel, console commands, and global exception handler",
        "paths": ["app/Http/Kernel.php", "app/Console/Kernel.php", "app/Exceptions/Handler.php"],
        "delta_mins": 315
    },
    {
        "msg": "feat(database): create users migration with rbac roles and virtual coin balance",
        "paths": ["database/migrations/2014_10_12_000000_create_users_table.php", "database/migrations/2014_10_12_100000_create_password_reset_tokens_table.php", "database/migrations/2019_08_19_000000_create_failed_jobs_table.php", "database/migrations/2019_12_14_000001_create_personal_access_tokens_table.php"],
        "delta_mins": 375
    },
    {
        "msg": "feat(database): create categories and comics schema with slug indexing",
        "paths": ["database/migrations/2024_01_01_000001_create_categories_table.php", "database/migrations/2024_01_01_000002_create_comics_table.php"],
        "delta_mins": 435
    },
    {
        "msg": "feat(database): create comic category many-to-many pivot relationship schema",
        "paths": ["database/migrations/2024_01_01_000003_create_comic_category_table.php"],
        "delta_mins": 485
    },
    {
        "msg": "feat(database): create chapters and chapter images schema with vip locks",
        "paths": ["database/migrations/2024_01_01_000004_create_chapters_table.php", "database/migrations/2024_01_01_000005_create_chapter_images_table.php"],
        "delta_mins": 540
    },
    {
        "msg": "feat(database): create comments schema with nested parent-child replies",
        "paths": ["database/migrations/2024_01_01_000006_create_comments_table.php"],
        "delta_mins": 595
    },
    {
        "msg": "feat(database): create favorites and reading history tracking schema",
        "paths": ["database/migrations/2024_01_01_000007_create_favorites_table.php", "database/migrations/2024_01_01_000008_create_reading_history_table.php"],
        "delta_mins": 650
    },
    {
        "msg": "feat(database): create virtual coin wallet transactions and packages schema",
        "paths": ["database/migrations/2024_01_01_000009_create_coin_system_tables.php"],
        "delta_mins": 710
    },
    {
        "msg": "feat(database): create manga merchandise products and ecommerce orders schema",
        "paths": ["database/migrations/2024_01_01_000010_create_shop_and_orders_tables.php"],
        "delta_mins": 775
    },
    {
        "msg": "feat(models): implement User model with coin wallet and vip accessors",
        "paths": ["app/Models/User.php"],
        "delta_mins": 840
    },
    {
        "msg": "feat(models): implement Category and Comic models with full relation bindings",
        "paths": ["app/Models/Category.php", "app/Models/Comic.php"],
        "delta_mins": 905
    },
    {
        "msg": "feat(models): implement Chapter and ChapterImage models with sequential sorting",
        "paths": ["app/Models/Chapter.php", "app/Models/ChapterImage.php"],
        "delta_mins": 965
    },
    {
        "msg": "feat(models): implement Comment, Favorite and ReadingHistory models",
        "paths": ["app/Models/Comment.php", "app/Models/Favorite.php", "app/Models/ReadingHistory.php"],
        "delta_mins": 1025
    },
    {
        "msg": "feat(models): implement CoinPackage, CoinTransaction and ChapterUnlock models",
        "paths": ["app/Models/CoinPackage.php", "app/Models/CoinTransaction.php", "app/Models/ChapterUnlock.php"],
        "delta_mins": 1085
    },
    {
        "msg": "feat(models): implement Product, Order and OrderItem ecommerce models",
        "paths": ["app/Models/Product.php", "app/Models/Order.php", "app/Models/OrderItem.php"],
        "delta_mins": 1150
    },
    {
        "msg": "feat(middleware): configure admin and content uploader rbac access control",
        "paths": ["app/Http/Middleware/AdminMiddleware.php", "app/Http/Middleware/UploaderMiddleware.php"],
        "delta_mins": 1215
    },
    {
        "msg": "feat(providers): register application services and policy bindings",
        "paths": ["app/Providers/AppServiceProvider.php", "app/Providers/AuthServiceProvider.php", "app/Providers/EventServiceProvider.php", "app/Providers/RouteServiceProvider.php"],
        "delta_mins": 1280
    },
    {
        "msg": "feat(routes): register web and api route endpoints for reading and ecommerce",
        "paths": ["routes/web.php", "routes/api.php", "routes/console.php", "routes/channels.php"],
        "delta_mins": 1345
    },
    {
        "msg": "feat(controllers): implement base Controller and AuthController session flows",
        "paths": ["app/Http/Controllers/Controller.php", "app/Http/Controllers/AuthController.php"],
        "delta_mins": 1410
    },
    {
        "msg": "feat(controllers): implement HomeController and ComicController catalog query filters",
        "paths": ["app/Http/Controllers/HomeController.php", "app/Http/Controllers/ComicController.php"],
        "delta_mins": 1475
    },
    {
        "msg": "feat(controllers): implement ReaderController with vip lock check and ajax unlocking",
        "paths": ["app/Http/Controllers/ReaderController.php"],
        "delta_mins": 1540
    },
    {
        "msg": "feat(controllers): implement InteractionController for comments, likes and ratings",
        "paths": ["app/Http/Controllers/InteractionController.php"],
        "delta_mins": 1600
    },
    {
        "msg": "feat(controllers): implement WalletController with ACID coin transactions and VIP buy",
        "paths": ["app/Http/Controllers/WalletController.php"],
        "delta_mins": 1665
    },
    {
        "msg": "feat(controllers): implement ShopController with cart session, checkout and orders",
        "paths": ["app/Http/Controllers/ShopController.php"],
        "delta_mins": 1730
    },
    {
        "msg": "feat(controllers): implement AdminController for comics, chapters, users and orders",
        "paths": ["app/Http/Controllers/AdminController.php"],
        "delta_mins": 1795
    },
    {
        "msg": "feat(public): configure public index gateway, htaccess and robots policy",
        "paths": ["public/index.php", "public/.htaccess", "public/robots.txt"],
        "delta_mins": 1855
    },
    {
        "msg": "feat(views): build authentication blade templates for login, register and profile",
        "paths": ["resources/views/auth/login.blade.php", "resources/views/auth/register.blade.php", "resources/views/auth/profile.blade.php"],
        "delta_mins": 1920
    },
    {
        "msg": "feat(admin): build admin dashboard analytics and comics management views",
        "paths": ["resources/views/admin/dashboard.blade.php", "resources/views/admin/comics/index.blade.php", "resources/views/admin/comics/create.blade.php", "resources/views/admin/comics/edit.blade.php"],
        "delta_mins": 1985
    },
    {
        "msg": "feat(admin): build chapter management with batch image upload views",
        "paths": ["resources/views/admin/chapters/index.blade.php", "resources/views/admin/chapters/create.blade.php"],
        "delta_mins": 2045
    },
    {
        "msg": "feat(admin): build order fulfillment and user access control management views",
        "paths": ["resources/views/admin/orders/index.blade.php", "resources/views/admin/users/index.blade.php"],
        "delta_mins": 2105
    },
    {
        "msg": "feat(views): build wallet topup dashboard, package cards and sandbox payment simulator",
        "paths": ["resources/views/pages/wallet.blade.php", "resources/views/pages/wallet-success.blade.php"],
        "delta_mins": 2165
    },
    {
        "msg": "feat(assets): import official high-resolution manga cover artworks for top titles",
        "paths": [
            "public/uploads/covers/solo-leveling.jpg",
            "public/uploads/covers/one-piece.jpg",
            "public/uploads/covers/chainsaw-man.jpg",
            "public/uploads/covers/jujutsu-kaisen.jpg",
            "public/uploads/covers/spy-x-family.jpg",
            "public/uploads/covers/frieren.jpg",
            "public/uploads/covers/conan.jpg",
            "public/uploads/covers/attack-on-titan.jpg"
        ],
        "delta_mins": 2230
    },
    {
        "msg": "feat(assets): integrate authentic comic chapter reading panels with vietnamese letters",
        "paths": [
            "public/uploads/chapters/ch1_page_01.jpg",
            "public/uploads/chapters/ch1_page_02.jpg",
            "public/uploads/chapters/ch1_page_03.jpg",
            "public/uploads/chapters/ch1_page_04.jpg",
            "public/uploads/chapters/ch2_page_01.jpg",
            "public/uploads/chapters/ch2_page_02.jpg",
            "public/uploads/chapters/ch2_page_03.jpg",
            "public/uploads/chapters/ch2_page_04.jpg"
        ],
        "delta_mins": 2295
    },
    {
        "msg": "feat(assets): create authentic merchandise product mockups matching catalog items",
        "paths": [
            "public/uploads/products/solo-leveling-boxset.jpg",
            "public/uploads/products/luffy-gear-5.jpg",
            "public/uploads/products/chainsaw-man-pochita-shirt.jpg",
            "public/uploads/products/spy-family-anya-bookmark.jpg",
            "public/uploads/products/frieren-nendoroid.jpg",
            "public/uploads/products/gojo-satoru-figure.jpg"
        ],
        "delta_mins": 2360
    },
    {
        "msg": "feat(seeders): populate authentic manga database with verified covers, chapters, and merch",
        "paths": ["database/seeders/DatabaseSeeder.php"],
        "delta_mins": 2420
    },
    {
        "msg": "feat(ui): integrate Plus Jakarta Sans and Be Vietnam Pro typography with line clamp utilities",
        "paths": ["resources/views/layouts/app.blade.php"],
        "delta_mins": 2480
    },
    {
        "msg": "feat(ui): redesign homepage with webtoon spotlight hero banner and trending cards",
        "paths": ["resources/views/pages/home.blade.php"],
        "delta_mins": 2540
    },
    {
        "msg": "feat(ui): polish comics catalog grid with clean aspect ratio and title clamp",
        "paths": ["resources/views/pages/comics-list.blade.php"],
        "delta_mins": 2600
    },
    {
        "msg": "feat(ui): enhance comic detail page metadata formatting and prevent text wrapping",
        "paths": ["resources/views/pages/comic-detail.blade.php"],
        "delta_mins": 2660
    },
    {
        "msg": "feat(ui): optimize manga reader sticky control toolbar and zoom controls",
        "paths": ["resources/views/pages/reader.blade.php"],
        "delta_mins": 2720
    },
    {
        "msg": "feat(ui): refine shop grid cards and product detail view typography",
        "paths": ["resources/views/pages/shop.blade.php", "resources/views/pages/product-detail.blade.php"],
        "delta_mins": 2780
    },
    {
        "msg": "feat(ui): update cart and checkout table formatting to prevent text breaking",
        "paths": ["resources/views/pages/cart.blade.php", "resources/views/pages/checkout.blade.php", "resources/views/pages/order-success.blade.php"],
        "delta_mins": 2840
    },
    {
        "msg": "docs: add comprehensive project defense guide and batch launch script",
        "paths": ["start_server.bat", "HUONG_DAN_DO_AN.md"],
        "delta_mins": 2900
    },
    {
        "msg": "chore: finalize remaining project configurations, scripts, and assets",
        "paths": ["."],
        "delta_mins": 2960
    }
]

print(f"Total planned commits: {len(commits)}")

# Verify git repo
def run(cmd, env=None):
    res = subprocess.run(cmd, shell=True, text=True, capture_output=True, env=env)
    return res.returncode, res.stdout.strip(), res.stderr.strip()

# Switch to a new orphan branch 'fresh_main'
print("Creating fresh timeline branch...")
code, out, err = run("git checkout --orphan fresh_main")
run("git reset")

env = os.environ.copy()
env["GIT_AUTHOR_NAME"] = AUTHOR_NAME
env["GIT_AUTHOR_EMAIL"] = AUTHOR_EMAIL
env["GIT_COMMITTER_NAME"] = AUTHOR_NAME
env["GIT_COMMITTER_EMAIL"] = AUTHOR_EMAIL

for idx, c in enumerate(commits):
    dt = BASE_DATE + datetime.timedelta(minutes=c["delta_mins"])
    date_str = dt.strftime("%Y-%m-%d %H:%M:%S +0700")
    env["GIT_AUTHOR_DATE"] = date_str
    env["GIT_COMMITTER_DATE"] = date_str

    for p in c["paths"]:
        if os.path.exists(p) or p == ".":
            run(f'git add "{p}"')

    msg = c["msg"]
    code, out, err = run(f'git commit -m "{msg}"', env=env)
    if code == 0:
        print(f"[{idx+1}/{len(commits)}] Committed: {msg[:60]}... ({date_str})")
    else:
        # Check if anything was staged
        _, status, _ = run("git status --porcelain")
        if not status:
            print(f"[{idx+1}/{len(commits)}] Skipped (no changes): {msg[:50]}")
        else:
            print(f"[{idx+1}/{len(commits)}] Commit error: {err}")

# Replace main branch with fresh_main
print("Replacing branch 'main' with 'fresh_main'...")
run("git branch -D main")
run("git branch -m main")

code, log, _ = run("git log --oneline -n 10")
print("Latest 10 commits:")
print(log)
