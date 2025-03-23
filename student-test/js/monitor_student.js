document.addEventListener("DOMContentLoaded", function () {
    const tables = document.querySelectorAll("table");

    tables.forEach((table) => {
        const headers = table.querySelectorAll("th");

        headers.forEach((header, index) => {
            header.addEventListener("click", function () {
                sortTable(table, index);
            });
        });

        // Add a search box above the table
        const searchInput = document.createElement("input");
        searchInput.setAttribute("type", "text");
        searchInput.setAttribute("placeholder", "Search...");
        searchInput.classList.add("table-search");
        table.parentNode.insertBefore(searchInput, table);

        searchInput.addEventListener("input", function () {
            filterTable(table, searchInput.value);
        });
    });

    function sortTable(table, columnIndex) {
        const rows = Array.from(table.rows).slice(1);
        const ascending = table.dataset.sortOrder !== "asc";

        rows.sort((rowA, rowB) => {
            const cellA = rowA.cells[columnIndex].textContent.trim();
            const cellB = rowB.cells[columnIndex].textContent.trim();
            return ascending ? cellA.localeCompare(cellB) : cellB.localeCompare(cellA);
        });

        table.tBodies[0].append(...rows);
        table.dataset.sortOrder = ascending ? "asc" : "desc";
    }

    function filterTable(table, query) {
        const rows = table.querySelectorAll("tbody tr");
        query = query.toLowerCase();

        rows.forEach((row) => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(query) ? "" : "none";
        });
    }
});
