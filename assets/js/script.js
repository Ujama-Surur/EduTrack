// DELETE CONFIRMATION
document.addEventListener("DOMContentLoaded", () => {

    const deleteButtons = document.querySelectorAll(".delete-btn");

    deleteButtons.forEach(btn => {
        btn.addEventListener("click", (e) => {
            if (!confirm("Are you sure you want to delete this?")) {
                e.preventDefault();
            }
        });
    });

});


// SEARCH FILTER (CLIENT SIDE)
function filterTable() {
    let input = document.getElementById("searchInput").value.toLowerCase();
    let rows = document.querySelectorAll("table tbody tr");

    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(input) ? "" : "none";
    });
}


// AUTO HIDE ALERTS
setTimeout(() => {
    let alerts = document.querySelectorAll(".alert");
    alerts.forEach(a => a.style.display = "none");
}, 3000);


// GPA COLOR INDICATOR
function highlightGPA(gpa) {
    let el = document.getElementById("gpa");

    if (gpa >= 3.5) {
        el.style.color = "green";
    } else if (gpa >= 2.0) {
        el.style.color = "orange";
    } else {
        el.style.color = "red";
    }
}
