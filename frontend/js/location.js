window.loadShopLocation = function () {
    const box = document.getElementById("shop-location-api");
    const map = document.getElementById("shop-map");
    if (!box) {
        return;
    }

    function esc(value) {
        return String(value == null ? "" : value)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;");
    }

    const api = window.KK_API || "http://localhost:8080";
    box.textContent = "Looking up the shop on OpenStreetMap…";

    fetch(api + "/api/location")
        .then(function (res) {
            return res.json().then(function (body) {
                return { ok: res.ok, body: body };
            });
        })
        .then(function (result) {
            if (!result.ok || !result.body.success) {
                throw new Error(result.body.message || "Location is unavailable");
            }
            const data = result.body.data;
            box.innerHTML =
                "<p class='mb-1'><strong>" + esc(data.display_name || data.query) + "</strong></p>" +
                "<p class='mb-2 text-muted'>Source: " + esc(data.provider) + "</p>" +
                (data.map_url
                    ? "<a class='btn btn-primary btn-sm' href='" + esc(data.map_url) + "' target='_blank' rel='noopener'>Open map</a>"
                    : "");
            if (map && data.embed_url) {
                map.src = data.embed_url;
            }
        })
        .catch(function (err) {
            box.innerHTML =
                "<p class='mb-0'>Could not load the live map. The shop is at Edina Džeke 11, Sarajevo.</p>" +
                "<p class='text-muted mb-0'>" + esc(err.message) + "</p>";
        });
};
