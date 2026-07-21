document.addEventListener("DOMContentLoaded", function () {

    function normalizeText(text) {
        return (text || "")
            .toString()
            .toLowerCase()
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .trim();
    }

    function initTableSearch() {

        const table = document.querySelector(".modern-table");
        const filterCard = document.querySelector(".module-filter-card");

        if (!table) {
            return;
        }

        const tbody = table.querySelector("tbody");

        if (!tbody) {
            return;
        }

        const allSelects = filterCard ? filterCard.querySelectorAll("select") : [];

        allSelects.forEach(function (select) {
            select.disabled = false;
        });

        const thCount = table.querySelectorAll("thead th").length || 1;

        let noResultRow = document.createElement("tr");
        noResultRow.classList.add("js-no-result-row");
        noResultRow.style.display = "none";

        noResultRow.innerHTML = `
            <td colspan="${thCount}" class="text-center py-5 text-muted">
                <i class="bi bi-search fs-1"></i>
                <br><br>
                Aucun résultat trouvé.
            </td>
        `;

        tbody.appendChild(noResultRow);

        function getRows() {
            return Array.from(tbody.querySelectorAll("tr")).filter(function (row) {
                return !row.classList.contains("js-no-result-row");
            });
        }

        function filterRows() {

            const localInput = filterCard ? filterCard.querySelector('input[name="search"]') : null;
            const navbarInput = document.querySelector(".search-box input");

            let searchValue = "";

            if (localInput && localInput.value.trim() !== "") {
                searchValue = localInput.value;
            } else if (navbarInput && navbarInput.value.trim() !== "") {
                searchValue = navbarInput.value;
            }

            const query = normalizeText(searchValue);
            const activeSelectValues = [];

            if (filterCard) {
                filterCard.querySelectorAll("select").forEach(function (select) {

                    const value = normalizeText(select.value);

                    if (
                        value !== "" &&
                        !value.includes("tous") &&
                        !value.includes("toutes") &&
                        !value.includes("choisir")
                    ) {
                        activeSelectValues.push(value);
                    }

                });
            }

            let visibleRows = 0;
            const rows = getRows();

            rows.forEach(function (row) {

                const rowText = normalizeText(row.innerText);

                const matchSearch = query === "" || rowText.includes(query);

                const matchSelects = activeSelectValues.every(function (value) {
                    return rowText.includes(value);
                });

                if (matchSearch && matchSelects) {
                    row.style.display = "";
                    visibleRows++;
                } else {
                    row.style.display = "none";
                }

            });

            if (rows.length > 0 && visibleRows === 0) {
                noResultRow.style.display = "";
            } else {
                noResultRow.style.display = "none";
            }
        }

        if (filterCard) {

            const form = filterCard.querySelector("form");

            if (form) {
                form.addEventListener("submit", function (e) {
                    e.preventDefault();
                    filterRows();
                });
            }

            const searchInput = filterCard.querySelector('input[name="search"]');

            if (searchInput) {
                searchInput.addEventListener("input", filterRows);
            }

            filterCard.querySelectorAll("select").forEach(function (select) {
                select.addEventListener("change", filterRows);
            });

            const resetButton = filterCard.querySelector('a[href*="page="]');

            if (resetButton) {
                resetButton.addEventListener("click", function (e) {
                    e.preventDefault();

                    const searchInput = filterCard.querySelector('input[name="search"]');

                    if (searchInput) {
                        searchInput.value = "";
                    }

                    filterCard.querySelectorAll("select").forEach(function (select) {
                        select.selectedIndex = 0;
                    });

                    const navbarInput = document.querySelector(".search-box input");

                    if (navbarInput) {
                        navbarInput.value = "";
                    }

                    filterRows();
                });
            }
        }

        const navbarSearch = document.querySelector(".search-box input");

        if (navbarSearch) {
            navbarSearch.addEventListener("input", filterRows);

            navbarSearch.addEventListener("keydown", function (e) {
                if (e.key === "Escape") {
                    navbarSearch.value = "";
                    filterRows();
                }
            });
        }

    }

    function initDashboardSearch() {

        const table = document.querySelector(".modern-table");

        if (table) {
            return;
        }

        const navbarInput = document.querySelector(".search-box input");

        if (!navbarInput) {
            return;
        }

        const dashboardContainer =
            document.querySelector(".dashboard-page") ||
            document.querySelector(".dashboard-container") ||
            document.querySelector(".dashboard-content") ||
            document.querySelector(".dashboard") ||
            document.querySelector(".content");

        const dashboardItems = Array.from(document.querySelectorAll(
            ".stat-card, " +
            ".dashboard-card, " +
            ".dashboard-stat-card, " +
            ".quick-card, " +
            ".quick-action-card, " +
            ".dashboard-action, " +
            ".activity-item, " +
            ".recent-card, " +
            ".recent-item, " +
            ".chart-card, " +
            ".kpi-card, " +
            ".welcome-card"
        )).filter(function (item) {
            return !item.closest(".sidebar") && !item.closest(".top-navbar");
        });

        if (dashboardItems.length === 0) {
            return;
        }

        let noResultMessage = document.querySelector(".dashboard-no-result");

        if (!noResultMessage) {
            noResultMessage = document.createElement("div");
            noResultMessage.className = "dashboard-no-result";
            noResultMessage.style.display = "none";

            noResultMessage.innerHTML = `
                <i class="bi bi-search"></i>
                <h5>Aucun résultat trouvé</h5>
                <p>Essayez avec un autre mot-clé.</p>
            `;

            if (dashboardContainer) {
                dashboardContainer.appendChild(noResultMessage);
            }
        }

        function filterDashboard() {

            const query = normalizeText(navbarInput.value);
            let visibleCount = 0;

            dashboardItems.forEach(function (item) {

                const itemText = normalizeText(item.innerText);

                if (query === "" || itemText.includes(query)) {
                    item.style.display = "";
                    visibleCount++;
                } else {
                    item.style.display = "none";
                }

            });

            if (query !== "" && visibleCount === 0) {
                noResultMessage.style.display = "block";
            } else {
                noResultMessage.style.display = "none";
            }
        }

        navbarInput.addEventListener("input", filterDashboard);

        navbarInput.addEventListener("keydown", function (e) {
            if (e.key === "Escape") {
                navbarInput.value = "";
                filterDashboard();
            }
        });

    }

    function initDeleteConfirmation() {

        const deleteLinks = document.querySelectorAll('a[href*="supprimer-"]');

        deleteLinks.forEach(function (link) {

            if (link.getAttribute("onclick")) {
                return;
            }

            link.addEventListener("click", function (e) {

                const confirmation = confirm("Voulez-vous vraiment supprimer cet élément ?");

                if (!confirmation) {
                    e.preventDefault();
                }

            });

        });

    }
function initAutoHideAlerts() {

    const alerts = document.querySelectorAll(".alert");

    alerts.forEach(function (alert) {

        setTimeout(function () {

            alert.style.transition = "0.4s ease";
            alert.style.opacity = "0";
            alert.style.transform = "translateY(-10px)";

            setTimeout(function () {
                alert.remove();
            }, 400);

        }, 3500);

    });

}

function initBackToTop() {

    let button = document.createElement("button");
    button.className = "back-to-top-btn";
    button.innerHTML = '<i class="bi bi-arrow-up"></i>';
    button.style.display = "none";

    document.body.appendChild(button);

    window.addEventListener("scroll", function () {

        if (window.scrollY > 250) {
            button.style.display = "flex";
        } else {
            button.style.display = "none";
        }

    });

    button.addEventListener("click", function () {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    });

}

function initFormValidation() {

    const forms = document.querySelectorAll("form");

    forms.forEach(function (form) {

        form.addEventListener("submit", function (e) {

            const requiredFields = form.querySelectorAll("[required]");
            let isValid = true;

            requiredFields.forEach(function (field) {

                if (field.value.trim() === "") {
                    isValid = false;
                    field.classList.add("is-invalid");
                } else {
                    field.classList.remove("is-invalid");
                }

            });

            if (!isValid) {
                e.preventDefault();
                alert("Veuillez remplir tous les champs obligatoires.");
            }

        });

    });

}

function initCardAnimations() {

    const cards = document.querySelectorAll(
        ".module-stat-card, .module-table-card, .module-filter-card, .modern-form-card, .dashboard-card, .stat-card"
    );

    cards.forEach(function (card) {

        card.addEventListener("mouseenter", function () {
            card.style.transform = "translateY(-3px)";
        });

        card.addEventListener("mouseleave", function () {
            card.style.transform = "translateY(0)";
        });

    });

}

    initTableSearch();
initDashboardSearch();
initDeleteConfirmation();
initAutoHideAlerts();
initBackToTop();
initFormValidation();
initCardAnimations();

});