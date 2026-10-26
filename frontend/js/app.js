(function () {
    const appEl = document.getElementById("app");

    const routes = {
        "#/home": "home.html",
        "#/about": "about.html",
        "#/service": "service.html",
        "#/menu": "menu.html",
        "#/reservation": "reservation.html",
        "#/reviews": "reviews.html",
        "#/contact": "contact.html",
        "#/login": "login.html",
        "#/register": "register.html",
        "#/dashboard": "dashboard.html",
        "#/profile": "profile.html",
        "#/products": "products.html",
        "#/categories": "categories.html",
        "#/orders": "orders.html",
        "#/users": "users.html",
        "#/weather": "weather.html"
    };

    function currentHash() {
        const hash = location.hash || "#/home";
        return routes[hash] ? hash : "#/home";
    }

    function setActiveNav(hash) {
        document.querySelectorAll(".navbar-nav .nav-link, .dropdown-menu .dropdown-item").forEach(function (el) {
            el.classList.remove("active", "spa-active");
        });
        const match = document.querySelector('.navbar-nav a[href="' + hash + '"]');
        if (match) {
            match.classList.add("active", "spa-active");
            const dropdown = match.closest(".dropdown");
            if (dropdown) {
                const toggle = dropdown.querySelector(".dropdown-toggle");
                if (toggle) {
                    toggle.classList.add("active", "spa-active");
                }
            }
        }
    }

    function reinitPlugins() {
        if (window.jQuery) {
            const $ = window.jQuery;
            if ($.fn.datetimepicker) {
                $(".date").datetimepicker({ format: "L" });
                $(".time").datetimepicker({ format: "LT" });
            }
            if ($.fn.owlCarousel) {
                $(".testimonial-carousel").owlCarousel({
                    autoplay: true,
                    smartSpeed: 1500,
                    margin: 30,
                    dots: true,
                    loop: true,
                    responsive: {
                        0: { items: 1 },
                        768: { items: 2 }
                    }
                });
            }
        }
    }

    async function loadView(hash) {
        const file = routes[hash] || routes["#/home"];
        try {
            const res = await fetch("./views/" + file, { cache: "no-cache" });
            if (!res.ok) {
                throw new Error("View not found: " + file);
            }
            const html = await res.text();
            appEl.innerHTML = html;
            setActiveNav(hash);
            window.scrollTo({ top: 0, behavior: "smooth" });
            reinitPlugins();
        } catch (err) {
            appEl.innerHTML = '<div class="container py-5"><div class="alert alert-danger">Failed to load page.</div></div>';
            console.error(err);
        }
    }

    window.addEventListener("hashchange", function () {
        loadView(currentHash());
    });

    if (!location.hash) {
        location.hash = "#/home";
    } else {
        loadView(currentHash());
    }
})();
