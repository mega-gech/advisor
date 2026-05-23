<?php 
require_once __DIR__ . '/../../controllers/AdvisorController.php';
$controller = new AdvisorController();
$data = $controller->getDashboardData();

$userName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Dr. Michael';
$firstName = 'Dr. Michael'; // Dynamic name fallback

include __DIR__ . '/../layouts/header.php'; 
?>

<div class="advisor-dashboard">
    <!-- Topbar Header -->
    <header class="topbar">
        <div class="search-container">
            <span class="search-icon">
                <svg class="icon" width="16" height="16"><use xlink:href="/public/img/icons.svg#search"></use></svg>
            </span>
            <input type="text" placeholder="Search students, messages, appointments...">
        </div>
        <div class="topbar-right">
            <div class="notification-bell">
                <svg class="icon" width="18" height="18"><use xlink:href="/public/img/icons.svg#bell"></use></svg>
                <span class="badge">6</span>
            </div>
            <div class="user-profile">
                <img src="https://ui-avatars.com/api/?name=Michael+Tesfaye&background=0b3c95&color=ffffff" alt="Avatar" class="avatar">
                <div class="user-info">
                    <span class="user-name">Dr. Michael Tesfaye</span>
                    <span class="user-role">Academic Advisor</span>
                </div>
                <span class="dropdown-icon">⌄</span>
            </div>
        </div>
    </header>

    <!-- Page Content Wrapper -->
    <div class="page-content">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div>
                <h1 class="welcome-title">Welcome back, Dr. Michael! 👋</h1>
                <p class="welcome-subtitle">Here's what's happening with your advisees today.</p>
            </div>
            <div class="date-picker">
                <span class="date-picker-icon">
                    <svg class="icon" width="16" height="16"><use xlink:href="/public/img/icons.svg#calendar"></use></svg>
                </span>
                May 25, 2025 | Sunday
            </div>
        </div>

        <!-- Metrics Grid (4 Columns) -->
        <div class="metrics-grid">
            <a href="#" class="metric-card blue">
                <div class="metric-icon-box blue">
                    <svg class="icon" width="22" height="22"><use xlink:href="/public/img/icons.svg#users"></use></svg>
                </div>
                <div class="metric-info">
                    <span class="metric-value">24</span>
                    <span class="metric-label">Assigned Students</span>
                    <span class="metric-link">View All Students →</span>
                </div>
            </a>
            <a href="#" class="metric-card green">
                <div class="metric-icon-box green">
                    <svg class="icon" width="22" height="22"><use xlink:href="/public/img/icons.svg#message"></use></svg>
                </div>
                <div class="metric-info">
                    <span class="metric-value">8</span>
                    <span class="metric-label">Unread Messages</span>
                    <span class="metric-link">View Messages →</span>
                </div>
            </a>
            <a href="#" class="metric-card orange">
                <div class="metric-icon-box orange">
                    <svg class="icon" width="22" height="22"><use xlink:href="/public/img/icons.svg#calendar"></use></svg>
                </div>
                <div class="metric-info">
                    <span class="metric-value">3</span>
                    <span class="metric-label">Upcoming Appointments</span>
                    <span class="metric-link">View Calendar →</span>
                </div>
            </a>
            <a href="#" class="metric-card purple">
                <div class="metric-icon-box purple">
                    <svg class="icon" width="22" height="22"><use xlink:href="/public/img/icons.svg#folder"></use></svg>
                </div>
                <div class="metric-info">
                    <span class="metric-value">5</span>
                    <span class="metric-label">Pending Requests</span>
                    <span class="metric-link">View Requests →</span>
                </div>
            </a>
        </div>

        <!-- Content Grid (2 Columns: My Advisees + Recent Messages) -->
        <div class="content-grid">
            <!-- My Advisees Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">My Advisees</h3>
                    <a href="#" class="card-action">View All</a>
                </div>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Last Contact</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="student-profile-cell">
                                        <img src="https://ui-avatars.com/api/?name=Abel+Eshetu&background=e2e8f0&color=334155" class="student-avatar-img" alt="Abel">
                                        <span class="student-table-name">Abel Eshetu</span>
                                    </div>
                                </td>
                                <td>abel.eshetu@aau.edu.et</td>
                                <td>Computer Science</td>
                                <td>May 24, 2025</td>
                                <td><span class="status-badge active">Active</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="student-profile-cell">
                                        <img src="https://ui-avatars.com/api/?name=Selamawit+K&background=e2e8f0&color=334155" class="student-avatar-img" alt="Selamawit">
                                        <span class="student-table-name">Selamawit K.</span>
                                    </div>
                                </td>
                                <td>selamawit.k@aau.edu.et</td>
                                <td>Software Engineering</td>
                                <td>May 23, 2025</td>
                                <td><span class="status-badge active">Active</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="student-profile-cell">
                                        <img src="https://ui-avatars.com/api/?name=Dawit+Tadesse&background=e2e8f0&color=334155" class="student-avatar-img" alt="Dawit">
                                        <span class="student-table-name">Dawit Tadesse</span>
                                    </div>
                                </td>
                                <td>dawit.t@aau.edu.et</td>
                                <td>Information Systems</td>
                                <td>May 22, 2025</td>
                                <td><span class="status-badge active">Active</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="student-profile-cell">
                                        <img src="https://ui-avatars.com/api/?name=Hanna+Mulu&background=fee2e2&color=b91c1c" class="student-avatar-img" alt="Hanna">
                                        <span class="student-table-name">Hanna Mulu</span>
                                    </div>
                                </td>
                                <td>hanna.m@aau.edu.et</td>
                                <td>Computer Science</td>
                                <td>May 21, 2025</td>
                                <td><span class="status-badge at-risk">At Risk</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="student-profile-cell">
                                        <img src="https://ui-avatars.com/api/?name=Yoseph+Abebe&background=e2e8f0&color=334155" class="student-avatar-img" alt="Yoseph">
                                        <span class="student-table-name">Yoseph Abebe</span>
                                    </div>
                                </td>
                                <td>yoseph.a@aau.edu.et</td>
                                <td>Software Engineering</td>
                                <td>May 20, 2025</td>
                                <td><span class="status-badge active">Active</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Messages Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Messages</h3>
                    <a href="#" class="card-action">View All</a>
                </div>
                
                <div class="list-item">
                    <div class="list-icon-box purple">MA</div>
                    <div class="list-content">
                        <div class="list-header">
                            <span class="list-title">Mekdes A.</span>
                            <span class="list-time">10:30 AM</span>
                        </div>
                        <div class="list-body">
                            <div class="list-desc">Thank you for your guidance on the project. I have a few...</div>
                            <span class="list-badge">2</span>
                        </div>
                    </div>
                </div>

                <div class="list-item">
                    <img src="https://ui-avatars.com/api/?name=Abel+Eshetu&background=e2e8f0&color=334155" class="list-avatar" alt="Abel">
                    <div class="list-content">
                        <div class="list-header">
                            <span class="list-title">Abel Eshetu</span>
                            <span class="list-time">Yesterday</span>
                        </div>
                        <div class="list-body">
                            <div class="list-desc">I would like to schedule a meeting to discuss my...</div>
                            <span class="list-badge">1</span>
                        </div>
                    </div>
                </div>

                <div class="list-item">
                    <div class="list-icon-box orange">DA</div>
                    <div class="list-content">
                        <div class="list-header">
                            <span class="list-title">Dawit Tadesse</span>
                            <span class="list-time">May 23</span>
                        </div>
                        <div class="list-body">
                            <div class="list-desc">Can you please review the document I uploaded?</div>
                        </div>
                    </div>
                </div>

                <div class="list-item">
                    <div class="list-icon-box green">SE</div>
                    <div class="list-content">
                        <div class="list-header">
                            <span class="list-title">Selamawit K.</span>
                            <span class="list-time">May 22</span>
                        </div>
                        <div class="list-body">
                            <div class="list-desc">I have a question about the upcoming exam.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3-Column Second Row Grid (Appointments + Announcements + Requests) -->
        <div class="content-grid-3">
            <!-- Upcoming Appointments Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Upcoming Appointments</h3>
                    <a href="#" class="card-action">View Calendar</a>
                </div>
                
                <div class="appointment-card-item">
                    <div class="calendar-badge">
                        <span class="month">May</span>
                        <span class="day">26</span>
                    </div>
                    <div class="appt-details">
                        <span class="appt-title">Meeting with Abel Eshetu</span>
                        <span class="appt-type">Academic advising</span>
                        <div class="appt-meta">
                            <span>10:00 AM - 10:30 AM</span> | <span class="appt-status confirmed">Confirmed</span>
                        </div>
                    </div>
                </div>

                <div class="appointment-card-item">
                    <div class="calendar-badge">
                        <span class="month">May</span>
                        <span class="day">27</span>
                    </div>
                    <div class="appt-details">
                        <span class="appt-title">Meeting with Selamawit K.</span>
                        <span class="appt-type">Project discussion</span>
                        <div class="appt-meta">
                            <span>11:00 AM - 11:30 AM</span> | <span class="appt-status confirmed">Confirmed</span>
                        </div>
                    </div>
                </div>

                <div class="appointment-card-item">
                    <div class="calendar-badge" style="background:#fff7ed; border-color:#ffedd5;">
                        <span class="month" style="color:#ea580c;">May</span>
                        <span class="day" style="color:#c2410c;">29</span>
                    </div>
                    <div class="appt-details">
                        <span class="appt-title">Group Advising Session</span>
                        <span class="appt-type">Advising multiple students</span>
                        <div class="appt-meta">
                            <span>02:00 PM - 03:00 PM</span> | <span class="appt-status pending">Pending</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Advisor Announcements Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Advisor Announcements</h3>
                    <a href="#" class="card-action">Send Broadcast</a>
                </div>

                <div class="announcement-item">
                    <div class="announcement-left">
                        <div class="list-icon-box blue">
                            <svg class="icon" width="18" height="18"><use xlink:href="/public/img/icons.svg#bell"></use></svg>
                        </div>
                        <div class="announcement-details">
                            <span class="announcement-title">New Academic Policy Update</span>
                            <span class="announcement-desc">Please inform your advisees about academic policy changes.</span>
                            <span class="announcement-date">May 24, 2025</span>
                        </div>
                    </div>
                    <button class="btn-broadcast-sm">Broadcast</button>
                </div>

                <div class="announcement-item">
                    <div class="announcement-left">
                        <div class="list-icon-box green">
                            <svg class="icon" width="18" height="18"><use xlink:href="/public/img/icons.svg#folder"></use></svg>
                        </div>
                        <div class="announcement-details">
                            <span class="announcement-title">Final Exam Schedule Released</span>
                            <span class="announcement-desc">Final exam schedule has been published, check and inform...</span>
                            <span class="announcement-date">May 22, 2025</span>
                        </div>
                    </div>
                    <button class="btn-broadcast-sm">Broadcast</button>
                </div>

                <div class="announcement-item">
                    <div class="announcement-left">
                        <div class="list-icon-box purple">
                            <svg class="icon" width="18" height="18"><use xlink:href="/public/img/icons.svg#document"></use></svg>
                        </div>
                        <div class="announcement-details">
                            <span class="announcement-title">Advising Week Announcement</span>
                            <span class="announcement-desc">Advising week will be held from June 3-7. Prepare schedules.</span>
                            <span class="announcement-date">May 20, 2025</span>
                        </div>
                    </div>
                    <button class="btn-broadcast-sm">Broadcast</button>
                </div>
            </div>

            <!-- Pending Requests Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pending Requests</h3>
                    <a href="#" class="card-action">View All</a>
                </div>

                <div class="request-item">
                    <div class="request-left">
                        <div class="list-icon-box orange">
                            <svg class="icon" width="16" height="16"><use xlink:href="/public/img/icons.svg#calendar"></use></svg>
                        </div>
                        <div class="request-info">
                            <span class="request-title">Appointment Request</span>
                            <span class="request-desc">3 students requested appointments</span>
                        </div>
                    </div>
                    <span class="request-badge">3</span>
                </div>

                <div class="request-item">
                    <div class="request-left">
                        <div class="list-icon-box purple">
                            <svg class="icon" width="16" height="16"><use xlink:href="/public/img/icons.svg#folder"></use></svg>
                        </div>
                        <div class="request-info">
                            <span class="request-title">Document Review</span>
                            <span class="request-desc">2 students submitted documents</span>
                        </div>
                    </div>
                    <span class="request-badge">2</span>
                </div>

                <div class="request-item">
                    <div class="request-left">
                        <div class="list-icon-box green">
                            <svg class="icon" width="16" height="16"><use xlink:href="/public/img/icons.svg#message"></use></svg>
                        </div>
                        <div class="request-info">
                            <span class="request-title">Feedback/Inquiry</span>
                            <span class="request-desc">1 student sent a feedback</span>
                        </div>
                    </div>
                    <span class="request-badge">1</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions Section -->
        <div>
            <h4 class="quick-actions-title">Quick Actions</h4>
            <div class="quick-actions">
                <a href="#" class="quick-pill">
                    <div class="quick-pill-icon">
                        <svg class="icon" width="18" height="18"><use xlink:href="/public/img/icons.svg#message"></use></svg>
                    </div>
                    <div class="quick-pill-details">
                        <span class="quick-pill-title">Send Message</span>
                        <span class="quick-pill-desc">Message a student</span>
                    </div>
                </a>
                <a href="#" class="quick-pill">
                    <div class="quick-pill-icon">
                        <svg class="icon" width="18" height="18"><use xlink:href="/public/img/icons.svg#calendar"></use></svg>
                    </div>
                    <div class="quick-pill-details">
                        <span class="quick-pill-title">Schedule Appointment</span>
                        <span class="quick-pill-desc">Book an advising session</span>
                    </div>
                </a>
                <a href="#" class="quick-pill">
                    <div class="quick-pill-icon" style="background:#f0fdf4; color:#16a34a;">
                        <svg class="icon" width="18" height="18"><use xlink:href="/public/img/icons.svg#folder"></use></svg>
                    </div>
                    <div class="quick-pill-details">
                        <span class="quick-pill-title">Upload Document</span>
                        <span class="quick-pill-desc">Share important files</span>
                    </div>
                </a>
                <a href="#" class="quick-pill">
                    <div class="quick-pill-icon" style="background:#faf5ff; color:#9333ea;">
                        <svg class="icon" width="18" height="18"><use xlink:href="/public/img/icons.svg#chart"></use></svg>
                    </div>
                    <div class="quick-pill-details">
                        <span class="quick-pill-title">View Reports</span>
                        <span class="quick-pill-desc">Student progress reports</span>
                    </div>
                </a>
                <a href="#" class="quick-pill">
                    <div class="quick-pill-icon" style="background:#fff7ed; color:#ea580c;">
                        <svg class="icon" width="18" height="18"><use xlink:href="/public/img/icons.svg#calendar"></use></svg>
                    </div>
                    <div class="quick-pill-details">
                        <span class="quick-pill-title">Academic Calendar</span>
                        <span class="quick-pill-desc">View academic events</span>
                    </div>
                </a>
            </div>
        </div>

    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
