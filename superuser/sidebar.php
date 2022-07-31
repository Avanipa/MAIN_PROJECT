<div class="navigation">
            <ul>
                <li>
                    <a href="">
                        <span class="icon"><i class="bi bi-app-indicator"></i></span>
                        <span class="title">Welcome Admin</span>
                    </a>
                </li>
                <li>
                    <a href="./dashboard">
                        <span class="icon"><i class="bi bi-house-door"></i></span>
                        <span class="title">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="./insert_alert">
                        <span class="icon"><i class="bi bi-exclamation-diamond"></i></span>
                        <span class="title">Alerts</span>
                    </a>
                </li>
                <li>
                    <a href="./insert_slide">
                        <span class="icon"><i class="bi bi-file-easel"></i></span>
                        <span class="title">Slides</span>
                    </a>
                </li>
                <li>
                    <a href="./officers">
                        <span class="icon"><i class="bi bi-kanban"></i></span>
                        <span class="title">Manage Officers</span>
                    </a>
                </li>
                <li>
                    <a href="./insert_schemes">
                        <span class="icon"><i class="bi bi-kanban"></i></span>
                        <span class="title">Insert Schemes</span>
                    </a>
                </li>
                <li>
                    <a href="./insert_services">
                        <span class="icon"><i class="bi bi-kanban"></i></span>
                        <span class="title">Insert Services</span>
                    </a>
                </li>
                <li>
                    <a href="./view_attendence">
                        <span class="icon"><i class="bi bi-kanban"></i></span>
                        <span class="title">View Attendance</span>
                    </a>
                </li> 
                <li>
                    <a href="./view_applied_leave">
                        <span class="icon"><i class="bi bi-kanban"></i></span>
                        <span class="title">View Leave</span>
                    </a>    
                </li>
                <li>
                    <a href="./pay_salary">
                        <span class="icon"><i class="bi bi-kanban"></i></span>
                        <span class="title">Salary</span>
                    </a>    
                </li>

                <li>
                    <a href="./logout">
                        <span class="icon"><i class="bi bi-kanban"></i></span>
                        <span class="title">Logout</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="main">
            <div class="top-bar">
                <div class="toggle">
                    <i class="bi bi-list"></i>
                </div>
                <!-- User Image -->
                <div class="userimage">
                    <img src="./css/icon/person-workspace.svg" alt="">
                </div>
            </div>

<script>
    // menubar toggle
    let toggle = document.querySelector('.toggle');
    let navigation = document.querySelector('.navigation');
    let main = document.querySelector('.main');
    toggle.onclick = function(){
        navigation.classList.toggle('active');
        main.classList.toggle('active');
    }

    // navigation on hover stay
    let list = document.querySelectorAll('.navigation li');
    function activeLink(){
        list.forEach((item) => 
        item.classList.remove('hovered'));
        this.classList.add('hovered');
    }
    list.forEach((item) => 
    item.addEventListener('mouseover', activeLink));
</script>