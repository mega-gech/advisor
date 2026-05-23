<?php 
require_once __DIR__ . '/../../controllers/StudentController.php';
$controller = new StudentController();
$data = $controller->getDashboardData();

$userName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Samuel';
$firstName = explode(' ', trim($userName))[0];

// Handle case where advisor is not yet assigned
$advisor = $data['advisor'] ? $data['advisor'] : null;

// Messages logic
$unreadMessages = count($data['messages']); // Mocking unread as all messages for UI

include __DIR__ . '/../layouts/header.php'; 
?>
<div class="student-dashboard">
            <!-- Top Header (inside student-dashboard for scoped styles) -->
            <header class="topbar">
                <div class="topbar-left">
                    <span class="menu-toggle"><svg class="icon" width="20" height="20"><use xlink:href="/public/img/icons.svg#menu"></use></svg></span>
                    <div class="search-container">
                        <span class="search-icon"><svg class="icon" width="16" height="16"><use xlink:href="/public/img/icons.svg#search"></use></svg></span>
                        <input type="text" placeholder="Search anything...">
                    </div>
                </div>
                <div class="topbar-right">
                    <div class="notification-bell"><svg class="icon" width="18" height="18"><use xlink:href="/public/img/icons.svg#bell"></use></svg>
                        <span class="badge">5</span>
                    </div>
                    <div class="user-profile">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($userName); ?>&background=e2e8f0&color=334155" alt="Avatar" class="avatar">
                        <div class="user-info">
                            <span class="user-name">Hi, <?php echo htmlspecialchars($firstName); ?></span>
                            <span class="user-role">Student</span>
                        </div>
                        <span class="dropdown-icon">⌄</span>
                    </div>
                </div>
            </header>
            <!-- Welcome -->
            <div class="welcome-section">
                <div>
                    <h1 class="welcome-title">Welcome back, <?php echo htmlspecialchars($firstName); ?>! 👋</h1>
                    <p class="welcome-subtitle">Here's what's happening with your academic journey.</p>
                </div>
                <div class="date-picker">
                    <span class="date-picker-icon"><svg class="icon" width="16" height="16"><use xlink:href="/public/img/icons.svg#calendar"></use></svg></span>
                    <?php echo date('M d, Y | l'); ?>
                </div>
            </div>

            <!-- Metrics Grid -->
            <div class="metrics-grid">
                <div class="metric-card">
                    <div class="metric-icon-box blue"><svg class="icon" width="22" height="22"><use xlink:href="/public/img/icons.svg#message"></use></svg></div>
                    <div class="metric-info">
                        <div class="metric-value">3</div>
                        <div class="metric-label">Unread Messages</div>
                        <a href="#" class="metric-link">View Messages →</a>
                    </div>
                </div>
                <div class="metric-card">
                    <div class="metric-icon-box green"><svg class="icon" width="22" height="22"><use xlink:href="/public/img/icons.svg#bell"></use></svg></div>
                    <div class="metric-info">
                        <div class="metric-value">5</div>
                        <div class="metric-label">Notifications</div>
                        <a href="#" class="metric-link">View All →</a>
                    </div>
                </div>
                <div class="metric-card">
                    <div class="metric-icon-box orange"><svg class="icon" width="22" height="22"><use xlink:href="/public/img/icons.svg#calendar"></use></svg></div>
                    <div class="metric-info">
                        <div class="metric-value">1</div>
                        <div class="metric-label">Upcoming Appointment</div>
                        <a href="#" class="metric-link">View Appointment →</a>
                    </div>
                </div>
                <div class="metric-card">
                    <div class="metric-icon-box purple"><svg class="icon" width="22" height="22"><use xlink:href="/public/img/icons.svg#folder"></use></svg></div>
                    <div class="metric-info">
                        <div class="metric-value">2</div>
                        <div class="metric-label">Documents</div>
                        <a href="#" class="metric-link">View Documents →</a>
                    </div>
                </div>
            </div>

            <!-- Content Grid 2x2 -->
            <div class="content-grid">
                <!-- My Advisor -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">My Advisor</h3>
                        <span class="card-action">•••</span>
                    </div>
                    <?php if ($advisor): ?>
                    <div class="advisor-profile">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($advisor['advisor_name']); ?>&background=1e293b&color=ffffff" alt="Advisor" class="advisor-avatar">
                        <div class="advisor-details">
                            <div class="advisor-name">Dr. <?php echo htmlspecialchars($advisor['advisor_name']); ?></div>
                            <div class="advisor-dept">Computer Science Department</div>
                            <div class="advisor-contact">
                                <span class="advisor-contact-icon"><svg class="icon" width="16" height="16"><use xlink:href="/public/img/icons.svg#mail"></use></svg></span> <?php echo htmlspecialchars($advisor['advisor_email']); ?>
                            </div>
                            <div class="advisor-contact">
                                <span class="advisor-contact-icon"><svg class="icon" width="16" height="16"><use xlink:href="/public/img/icons.svg#phone"></use></svg></span> +251 91 234 5678
                            </div>
                            <button class="btn-outline">Send Message</button>
                        </div>
                    </div>
                    <?php else: ?>
                    <div style="padding: 24px; text-align: center; color: #64748b;">
                        You have not been assigned an advisor yet.
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Recent Messages -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Recent Messages</h3>
                        <a href="#" class="card-action">View All</a>
                    </div>
                    
                    <?php 
                    // Mock some recent messages if database is empty for visual matching, 
                    // otherwise use real ones but format them like the mockup.
                    $messagesToShow = array_slice($data['messages'], 0, 3);
                    if (empty($messagesToShow)): 
                    ?>
                    <!-- Mock Data for visual matching -->
                    <div class="list-item">
                        <img src="https://ui-avatars.com/api/?name=Michael+Tesfaye&background=1e293b&color=ffffff" class="list-avatar">
                        <div class="list-content">
                            <div class="list-header">
                                <span class="list-title">Dr. Michael Tesfaye</span>
                                <span class="list-time">10:30 AM</span>
                            </div>
                            <div class="list-body">
                                <div class="list-desc">Please review the attached document and let me know...</div>
                                <span class="list-badge">1</span>
                            </div>
                        </div>
                    </div>
                    <div class="list-item">
                        <div class="list-icon-box purple">R</div>
                        <div class="list-content">
                            <div class="list-header">
                                <span class="list-title">Registrar Office</span>
                                <span class="list-time">Yesterday</span>
                            </div>
                            <div class="list-desc">Your course registration for the next semester is now open.</div>
                        </div>
                    </div>
                    <div class="list-item">
                        <div class="list-icon-box green">AO</div>
                        <div class="list-content">
                            <div class="list-header">
                                <span class="list-title">Academic Office</span>
                                <span class="list-time">May 23</span>
                            </div>
                            <div class="list-desc">Important notice: Final exam schedule has been published.</div>
                        </div>
                    </div>
                    <?php else: ?>
                        <?php foreach($messagesToShow as $msg): 
                            $senderInitial = strtoupper(substr($msg['sender_name'], 0, 1));
                        ?>
                        <div class="list-item">
                            <div class="list-icon-box" style="background:#e2e8f0; color:#334155;"><?php echo $senderInitial; ?></div>
                            <div class="list-content">
                                <div class="list-header">
                                    <span class="list-title"><?php echo htmlspecialchars($msg['sender_name']); ?></span>
                                    <span class="list-time"><?php echo date('M d', strtotime($msg['sent_at'])); ?></span>
                                </div>
                                <div class="list-desc"><?php echo htmlspecialchars(substr($msg['message'], 0, 60)) . '...'; ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Upcoming Appointment -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Upcoming Appointment</h3>
                    </div>
                    <div class="appointment-box">
                        <div class="appointment-icon"><svg class="icon" width="28" height="28"><use xlink:href="/public/img/icons.svg#calendar"></use></svg></div>
                        <div class="appointment-info">
                            <h4>Meeting with <?php echo $advisor ? 'Dr. ' . htmlspecialchars($advisor['advisor_name']) : 'Dr. Michael Tesfaye'; ?></h4>
                            <p>Academic advising session</p>
                            <div class="appointment-detail">
                                <span><svg class="icon" width="14" height="14"><use xlink:href="/public/img/icons.svg#calendar"></use></svg></span> May 28, 2025 (Wednesday)
                            </div>
                            <div class="appointment-detail">
                                <span><svg class="icon" width="14" height="14"><use xlink:href="/public/img/icons.svg#clock"></use></svg></span> 10:00 AM
                            </div>
                            <div class="appointment-detail">
                                <span><svg class="icon" width="14" height="14"><use xlink:href="/public/img/icons.svg#location"></use></svg></span> Online (Google Meet)
                            </div>
                        </div>
                    </div>
                    <button class="btn-outline" style="width: 100%; margin-top:0;">View All Appointments</button>
                </div>

                <!-- Important Notifications -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Important Notifications</h3>
                        <a href="#" class="card-action">View All</a>
                    </div>
                    <div class="list-item">
                        <div class="list-icon-box purple"><svg class="icon" width="18" height="18"><use xlink:href="/public/img/icons.svg#bell"></use></svg></div>
                        <div class="list-content">
                            <div class="list-header">
                                <span class="list-title">Course registration is now open</span>
                                <span class="list-time">May 24, 2025</span>
                            </div>
                            <div class="list-desc">Register your courses for the upcoming semester.</div>
                        </div>
                    </div>
                    <div class="list-item">
                        <div class="list-icon-box green"><svg class="icon" width="18" height="18"><use xlink:href="/public/img/icons.svg#folder"></use></svg></div>
                        <div class="list-content">
                            <div class="list-header">
                                <span class="list-title">Library maintenance</span>
                                <span class="list-time">May 22, 2025</span>
                            </div>
                            <div class="list-desc">The central library will be closed on May 30 for maintenance.</div>
                        </div>
                    </div>
                    <div class="list-item">
                        <div class="list-icon-box orange"><svg class="icon" width="18" height="18"><use xlink:href="/public/img/icons.svg#document"></use></svg></div>
                        <div class="list-content">
                            <div class="list-header">
                                <span class="list-title">Scholarship application</span>
                                <span class="list-time">May 21, 2025</span>
                            </div>
                            <div class="list-desc">Apply for the merit-based scholarship before June 15.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Access -->
            <div>
                <h4 style="font-size:0.95rem; font-weight:700; margin-bottom:16px; color:#0f172a;">Quick Access</h4>
                <div class="quick-access">
                    <div class="quick-pill">
                        <div class="quick-pill-left">
                            <span class="quick-pill-icon"><svg class="icon" width="18" height="18"><use xlink:href="/public/img/icons.svg#book"></use></svg></span> My Courses
                        </div>
                        <span class="quick-pill-arrow">›</span>
                    </div>
                    <div class="quick-pill">
                        <div class="quick-pill-left">
                            <span class="quick-pill-icon"><svg class="icon" width="18" height="18"><use xlink:href="/public/img/icons.svg#clipboard"></use></svg></span> Assignments
                        </div>
                        <span class="quick-pill-arrow">›</span>
                    </div>
                    <div class="quick-pill">
                        <div class="quick-pill-left">
                            <span class="quick-pill-icon"><svg class="icon" width="18" height="18"><use xlink:href="/public/img/icons.svg#chart"></use></svg></span> Grades
                        </div>
                        <span class="quick-pill-arrow">›</span>
                    </div>
                    <div class="quick-pill">
                        <div class="quick-pill-left">
                            <span class="quick-pill-icon"><svg class="icon" width="18" height="18"><use xlink:href="/public/img/icons.svg#calendar"></use></svg></span> Academic Calendar
                        </div>
                        <span class="quick-pill-arrow">›</span>
                    </div>
                    <div class="quick-pill">
                        <div class="quick-pill-left">
                            <span class="quick-pill-icon"><svg class="icon" width="18" height="18"><use xlink:href="/public/img/icons.svg#folder"></use></svg></span> Resources
                        </div>
                        <span class="quick-pill-arrow">›</span>
                    </div>
                </div>
            </div>
            
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
