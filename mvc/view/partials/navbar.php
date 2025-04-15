<style>
    
/* Top Navigation Bar */
.topnav {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: 60px;
    background: #ffffff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    z-index: 99;
    display: flex;
    align-items: center;
    padding: 0 20px 0 270px; /* Adjusted for sidebar */
}

.topnav ul {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
}

.topnav li {
    margin-right: 20px;
}

.topnav a {
    color: #34495e;
    text-decoration: none;
    font-weight: 500;
    padding: 8px 12px;
    border-radius: 4px;
    transition: all 0.3s;
}

.topnav a:hover {
    background: #f5f5f5;
    color: #3498db;
}

</style>
<nav class="topnav">
    <ul>
        <li><a href="/evaluation/index">Create Evaluation</a></li>
        <li><a href="/evaluation/list">List Evaluations</a></li>
        <li><a href="/auth/logout">Logout</a></li>
    </ul>
</nav>
