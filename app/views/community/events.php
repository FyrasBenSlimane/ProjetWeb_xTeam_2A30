<?php
// Community Events page

// Get the events from the data passed by the controller
$events = $data['events'] ?? [];
$isLoggedIn = $data['is_logged_in'] ?? false;
$currentUserId = $data['user_id'] ?? 0;

// Include header
require APPROOT . '/views/layouts/header.php';
?>

<style>
    /* Global styles */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* Page layout */
    .events-container {
        background-color: #f9fafb;
        padding: 3rem 1rem;
    }

    .page-title {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .page-description {
        color: #6b7280;
        margin-bottom: 2rem;
        max-width: 768px;
    }

    /* Tabs */
    .tabs-container {
        display: inline-flex;
        height: 2.5rem;
        align-items: center;
        justify-content: center;
        background-color: white;
        border-radius: 0.375rem;
        padding: 0.25rem;
        border: 1px solid #e5e7eb;
        margin-bottom: 1.5rem;
    }

    .tab-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
        border-radius: 0.25rem;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s ease;
        cursor: pointer;
        border: none;
        background: none;
    }

    .tab-button.active {
        background-color: #2c3e50;
        color: white;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .tab-button:not(.active) {
        color: #4b5563;
    }

    .tab-content {
        display: block;
    }

    .tab-content.hidden {
        display: none;
    }

    /* Event cards */
    .events-grid {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 1.5rem;
    }

    @media (min-width: 768px) {
        .events-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 1024px) {
        .events-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    .event-card {
        background-color: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        transition: box-shadow 0.2s ease;
    }

    .event-card:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .event-content {
        padding: 1.5rem;
    }

    .event-type {
        display: inline-block;
        background-color: rgba(44, 62, 80, 0.1);
        color: #2c3e50;
        font-size: 0.875rem;
        font-weight: 500;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        margin-bottom: 0.5rem;
    }

    .event-title {
        font-size: 1.25rem;
        font-weight: 500;
        margin-bottom: 0.25rem;
        color: #111827;
    }

    .event-date {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 0.75rem;
    }

    .event-description {
        color: #4b5563;
        margin-bottom: 1rem;
    }

    .event-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .attendees {
        font-size: 0.875rem;
    }

    .attendees-count {
        font-weight: 500;
        color: #374151;
    }

    .attendees-label {
        color: #6b7280;
    }

    .days-until {
        font-size: 0.875rem;
        font-weight: 500;
        color: #2c3e50;
    }

    .event-actions {
        border-top: 1px solid #e5e7eb;
        padding: 1rem;
        display: flex;
        justify-content: flex-end;
    }

    .register-button {
        display: inline-block;
        background-color: #2c3e50;
        color: white;
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        transition: background-color 0.2s ease;
        text-decoration: none;
        cursor: pointer;
    }
    
    .registered-button {
        display: inline-block;
        background-color: #34d399;
        color: white;
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        text-decoration: none;
        cursor: default;
    }

    .register-button:hover {
        background-color: #34495e;
    }

    /* Calendar view */
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 2rem;
    }

    @media (min-width: 768px) {
        .calendar-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    .calendar-container {
        background-color: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 1.5rem;
    }

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .calendar-nav-button {
        padding: 0.25rem;
        border-radius: 9999px;
        background: none;
        border: none;
        cursor: pointer;
    }

    .calendar-nav-button:hover {
        background-color: #f3f4f6;
    }

    .calendar-month-title {
        font-weight: 500;
    }

    .calendar-days-header {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 0.25rem;
        margin-bottom: 0.25rem;
    }

    .day-name {
        text-align: center;
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 500;
    }

    .calendar-days-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 0.25rem;
    }

    .calendar-day {
        height: 2.5rem;
        border-radius: 0.375rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        cursor: pointer;
        border: none;
        background: none;
    }

    .calendar-day:hover:not(.today):not(.has-event) {
        background-color: #f3f4f6;
    }

    .calendar-day.today {
        background-color: #2c3e50;
        color: white;
    }

    .calendar-day.has-event {
        background-color: rgba(44, 62, 80, 0.1);
        color: #2c3e50;
    }

    .calendar-day.empty {
        cursor: default;
    }

    /* Day events */
    .day-events-section-title {
        font-size: 1.125rem;
        font-weight: 500;
        margin-bottom: 1rem;
    }

    .day-event-card {
        background-color: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: box-shadow 0.2s ease;
    }

    .day-event-card:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .day-event-title {
        font-weight: 500;
    }

    .day-event-time {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 0.5rem;
    }

    .day-event-description {
        font-size: 0.875rem;
        color: #4b5563;
        margin-bottom: 0.75rem;
    }

    .day-event-actions {
        display: flex;
        justify-content: flex-end;
    }

    .day-event-register {
        display: inline-block;
        background-color: #2c3e50;
        color: white;
        font-size: 0.875rem;
        font-weight: 500;
        padding: 0.25rem 0.75rem;
        border-radius: 0.375rem;
        text-decoration: none;
        transition: background-color 0.2s ease;
        cursor: pointer;
    }

    .day-event-register:hover {
        background-color: #34495e;
    }

    .no-events-message {
        color: #6b7280;
    }

    /* CTA section */
    .cta-container {
        margin-top: 3rem;
        background: linear-gradient(to right, #2c3e50, #1a252f);
        border-radius: 0.75rem;
        color: white;
        padding: 2rem;
        text-align: center;
    }

    .cta-title {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 1rem;
    }

    .cta-description {
        font-size: 1.125rem;
        margin-bottom: 1.5rem;
        max-width: 42rem;
        margin-left: auto;
        margin-right: auto;
    }

    .cta-button-primary {
        display: inline-block;
        background-color: #2c3e50;
        color: white;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        border-radius: 0.375rem;
        text-decoration: none;
        transition: background-color 0.2s ease;
        margin-right: 1rem;
    }

    .cta-button-primary:hover {
        background-color: #34495e;
    }

    .cta-button-secondary {
        display: inline-block;
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        border-radius: 0.375rem;
        text-decoration: none;
        transition: background-color 0.2s ease;
    }

    .cta-button-secondary:hover {
        background-color: rgba(255, 255, 255, 0.3);
    }

    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 2000;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s ease;
    }

    .modal-overlay.active {
        opacity: 1;
        pointer-events: auto;
    }

    .modal-container {
        background-color: white;
        border-radius: 0.5rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        transform: translateY(-20px);
        transition: transform 0.3s ease;
    }

    .modal-overlay.active .modal-container {
        transform: translateY(0);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.25rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .modal-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1a252f;
    }

    .modal-close {
        background: none;
        border: none;
        cursor: pointer;
        color: #6b7280;
        padding: 0.25rem;
        transition: color 0.2s ease;
    }

    .modal-close:hover {
        color: #1a252f;
    }

    .modal-body {
        padding: 1.25rem;
    }

    .modal-event-info {
        margin-bottom: 1.5rem;
    }

    .modal-event-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1a252f;
        margin-bottom: 0.5rem;
    }

    .modal-event-date {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 0.5rem;
    }

    .modal-event-description {
        font-size: 0.875rem;
        color: #4b5563;
    }

    .modal-form-group {
        margin-bottom: 1rem;
    }

    .modal-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #1a252f;
        margin-bottom: 0.375rem;
    }

    .modal-input {
        width: 100%;
        padding: 0.625rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
        font-size: 0.875rem;
    }

    .modal-input:focus {
        outline: none;
        border-color: #2c3e50;
        box-shadow: 0 0 0 2px rgba(44, 62, 80, 0.1);
    }

    .modal-text {
        font-size: 0.875rem;
        color: #4b5563;
        margin-bottom: 1rem;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        padding: 1.25rem;
        border-top: 1px solid #e5e7eb;
        gap: 0.75rem;
    }

    .modal-button {
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .modal-button-primary {
        background-color: #2c3e50;
        color: white;
        border: none;
    }

    .modal-button-primary:hover {
        background-color: #1a252f;
    }

    .modal-button-secondary {
        background-color: white;
        color: #4b5563;
        border: 1px solid #e5e7eb;
    }

    .modal-button-secondary:hover {
        background-color: #f9fafb;
    }

    .login-button {
        background-color: #2c3e50;
        color: white;
        font-weight: 500;
        border: none;
        padding: 0.625rem 1.25rem;
        border-radius: 0.375rem;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .login-button:hover {
        background-color: #1a252f;
    }

    .signup-link {
        display: block;
        text-align: center;
        margin-top: 1rem;
        font-size: 0.875rem;
        color: #4b5563;
    }

    .signup-link a {
        color: #2c3e50;
        font-weight: 500;
        text-decoration: none;
    }

    .signup-link a:hover {
        text-decoration: underline;
    }
</style>

<div class="events-container">
    <div class="container">
        <h1 class="page-title">Community Events</h1>
        <p class="page-description">
            Join virtual events, workshops, and meetups with fellow professionals to learn, network, and grow together.
        </p>
        
        <?php flash('event_message'); ?>
        
        <!-- Tabs for Upcoming Events & Calendar View -->
        <div class="tabs-container">
            <button onclick="showTab('upcoming')" class="tab-button upcoming-tab active">
                Upcoming Events
            </button>
            <button onclick="showTab('calendar')" class="tab-button calendar-tab">
                Calendar View
            </button>
            <?php if ($isLoggedIn): ?>
            <button onclick="showTab('my-events')" class="tab-button my-events-tab">
                My Events
            </button>
            <?php endif; ?>
        </div>
        
        <!-- Upcoming Events Tab Content -->
        <div id="upcoming-tab-content" class="tab-content">
            <div class="events-grid">
                <?php 
                if (!empty($events)): 
                    foreach ($events as $event):
                        // Check if the user is already registered for this event
                        $isRegistered = false;
                        if (!empty($data['registrations']) && $isLoggedIn) {
                            foreach ($data['registrations'] as $registration) {
                                if ($registration->event_id == $event->id) {
                                    $isRegistered = true;
                                    break;
                                }
                            }
                        }
                        
                        // Calculate days until the event
                        $eventDate = new DateTime($event->start_date);
                    $now = new DateTime();
                    $interval = $now->diff($eventDate);
                    $daysUntil = $interval->days;
                        
                        // Format the duration (calculate from start_date to end_date)
                        $endDate = new DateTime($event->end_date);
                        $durationInterval = $eventDate->diff($endDate);
                        $hours = $durationInterval->h + ($durationInterval->days * 24);
                        $minutes = $durationInterval->i;
                        
                        if ($hours > 0 && $minutes > 0) {
                            $duration = $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ' . $minutes . ' min';
                        } elseif ($hours > 0) {
                            $duration = $hours . ' hour' . ($hours > 1 ? 's' : '');
                        } else {
                            $duration = $minutes . ' minutes';
                        }
                ?>
                <div class="event-card">
                    <div class="event-content">
                        <div class="event-type">
                            <?php echo htmlspecialchars(ucfirst($event->event_type)); ?>
                        </div>
                        <h3 class="event-title"><?php echo htmlspecialchars($event->title); ?></h3>
                        <p class="event-date">
                            <?php echo $eventDate->format('D, M j, Y g:i A'); ?> • <?php echo $duration; ?>
                        </p>
                        <p class="event-description"><?php echo htmlspecialchars(substr($event->description, 0, 150)) . (strlen($event->description) > 150 ? '...' : ''); ?></p>
                        <div class="event-meta">
                            <div class="attendees">
                                <span class="attendees-count"><?php echo isset($event->registrations_count) ? $event->registrations_count : 0; ?></span> 
                                <span class="attendees-label">attending</span>
                            </div>
                            <div class="days-until">
                                <?php if ($daysUntil == 0): ?>
                                    Today
                                <?php else: ?>
                                    In <?php echo $daysUntil; ?> day<?php echo $daysUntil > 1 ? 's' : ''; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="event-actions">
                        <?php if ($isRegistered): ?>
                            <span class="registered-button">
                                Registered
                            </span>
                        <?php else: ?>
                            <a href="javascript:void(0)" onclick="handleEventRegistration(<?php echo $event->id; ?>, '<?php echo addslashes(htmlspecialchars($event->title)); ?>', '<?php echo addslashes($eventDate->format('D, M j, Y g:i A')); ?>', '<?php echo addslashes($duration); ?>', '<?php echo addslashes(htmlspecialchars($event->description)); ?>', <?php echo $event->is_virtual; ?>)" class="register-button">
                            Register
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php 
                    endforeach; 
                else: 
                ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 2rem;">
                    <p>No upcoming events found. Check back soon!</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Calendar View Tab Content -->
        <div id="calendar-tab-content" class="tab-content hidden">
            <div class="calendar-grid">
                <div>
                    <div class="calendar-container">
                        <div id="calendar" class="calendar">
                            <?php
                            $currentMonth = date('F Y');
                            $daysInMonth = date('t');
                            $firstDayOfMonth = date('N', strtotime(date('Y-m-01')));
                            
                            // Adjust to make Monday the first day (1) through Sunday (7)
                            if ($firstDayOfMonth == 7) {
                                $firstDayOfMonth = 0;
                            }
                            ?>
                            
                            <div class="calendar-header">
                                <button class="calendar-nav-button" id="prevMonth">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <h3 class="calendar-month-title"><?php echo $currentMonth; ?></h3>
                                <button class="calendar-nav-button" id="nextMonth">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="calendar-days-header">
                                <?php 
                                $days = ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'];
                                foreach ($days as $day): 
                                ?>
                                <div class="day-name">
                                    <?php echo $day; ?>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="calendar-days-grid" id="calendar-days">
                                <?php
                                // Print empty cells for days before the start of the month
                                for ($i = 0; $i < $firstDayOfMonth; $i++): 
                                ?>
                                <div class="calendar-day empty"></div>
                                <?php 
                                endfor;
                                
                                // Print the days of the month
                                for ($day = 1; $day <= $daysInMonth; $day++):
                                    $isToday = date('j') == $day;
                                    $hasEvent = false;
                                    
                                    // Check if any event falls on this day
                                    foreach ($events as $event) {
                                        $eventDate = new DateTime($event->start_date);
                                        if ($eventDate->format('j') == $day && $eventDate->format('n') == date('n')) {
                                            $hasEvent = true;
                                            break;
                                        }
                                    }
                                    
                                    $classes = "calendar-day";
                                    if ($isToday) $classes .= " today";
                                    else if ($hasEvent) $classes .= " has-event";
                                ?>
                                <button onclick="showDayEvents(<?php echo $day; ?>)" class="<?php echo $classes; ?>">
                                    <?php echo $day; ?>
                                </button>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="day-events-section-title">Events for <span id="selected-date"><?php echo date('F j, Y'); ?></span></h3>
                    
                    <div id="day-events">
                        <?php
                        $todayEvents = [];
                        foreach ($events as $event) {
                            $eventDate = new DateTime($event->start_date);
                            if ($eventDate->format('j') == date('j') && $eventDate->format('n') == date('n')) {
                                $todayEvents[] = $event;
                            }
                        }
                        
                        if (count($todayEvents) > 0):
                            foreach ($todayEvents as $event):
                                $eventDate = new DateTime($event->start_date);
                                $endDate = new DateTime($event->end_date);
                                $durationInterval = $eventDate->diff($endDate);
                                $hours = $durationInterval->h + ($durationInterval->days * 24);
                                $minutes = $durationInterval->i;
                                
                                if ($hours > 0 && $minutes > 0) {
                                    $duration = $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ' . $minutes . ' min';
                                } elseif ($hours > 0) {
                                    $duration = $hours . ' hour' . ($hours > 1 ? 's' : '');
                                } else {
                                    $duration = $minutes . ' minutes';
                                }
                                
                                // Check if the user is already registered
                                $isRegistered = false;
                                if (!empty($data['registrations']) && $isLoggedIn) {
                                    foreach ($data['registrations'] as $registration) {
                                        if ($registration->event_id == $event->id) {
                                            $isRegistered = true;
                                            break;
                                        }
                                    }
                                }
                        ?>
                        <div class="day-event-card">
                            <h4 class="day-event-title"><?php echo htmlspecialchars($event->title); ?></h4>
                            <p class="day-event-time">
                                <?php echo $eventDate->format('g:i A'); ?> • <?php echo $duration; ?>
                            </p>
                            <p class="day-event-description"><?php echo htmlspecialchars(substr($event->description, 0, 150)) . (strlen($event->description) > 150 ? '...' : ''); ?></p>
                            <div class="day-event-actions">
                                <?php if ($isRegistered): ?>
                                    <span class="registered-button">
                                        Registered
                                    </span>
                                <?php else: ?>
                                    <a href="javascript:void(0)" onclick="handleEventRegistration(<?php echo $event->id; ?>, '<?php echo addslashes(htmlspecialchars($event->title)); ?>', '<?php echo addslashes($eventDate->format('D, M j, Y g:i A')); ?>', '<?php echo addslashes($duration); ?>', '<?php echo addslashes(htmlspecialchars($event->description)); ?>', <?php echo $event->is_virtual; ?>)" class="day-event-register">
                                    Register
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php 
                            endforeach;
                        else:
                        ?>
                        <p class="no-events-message">No events scheduled for today.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- My Events Tab Content -->
        <?php if ($isLoggedIn): ?>
        <div id="my-events-tab-content" class="tab-content hidden">
            <div class="events-grid">
                <?php 
                if (!empty($data['registrations'])): 
                    foreach ($data['registrations'] as $registration):
                        // Find the corresponding event
                        $event = null;
                        foreach ($events as $e) {
                            if ($e->id == $registration->event_id) {
                                $event = $e;
                                break;
                            }
                        }
                        
                        if ($event):
                            // Calculate days until the event
                            $eventDate = new DateTime($event->start_date);
                            $now = new DateTime();
                            $interval = $now->diff($eventDate);
                            $daysUntil = $interval->days;
                            
                            // Format the duration (calculate from start_date to end_date)
                            $endDate = new DateTime($event->end_date);
                            $durationInterval = $eventDate->diff($endDate);
                            $hours = $durationInterval->h + ($durationInterval->days * 24);
                            $minutes = $durationInterval->i;
                            
                            if ($hours > 0 && $minutes > 0) {
                                $duration = $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ' . $minutes . ' min';
                            } elseif ($hours > 0) {
                                $duration = $hours . ' hour' . ($hours > 1 ? 's' : '');
                            } else {
                                $duration = $minutes . ' minutes';
                            }
                ?>
                <div class="event-card">
                    <div class="event-content">
                        <div class="event-type">
                            <?php echo htmlspecialchars(ucfirst($event->event_type)); ?>
                        </div>
                        <h3 class="event-title"><?php echo htmlspecialchars($event->title); ?></h3>
                        <p class="event-date">
                            <?php echo $eventDate->format('D, M j, Y g:i A'); ?> • <?php echo $duration; ?>
                        </p>
                        <p class="event-description"><?php echo htmlspecialchars(substr($event->description, 0, 150)) . (strlen($event->description) > 150 ? '...' : ''); ?></p>
                        <div class="event-meta">
                            <div class="attendees">
                                <span class="attendees-count"><?php echo isset($event->registrations_count) ? $event->registrations_count : 0; ?></span> 
                                <span class="attendees-label">attending</span>
                            </div>
                            <div class="days-until">
                                <?php if ($daysUntil == 0): ?>
                                    Today
                                <?php elseif ($daysUntil < 0): ?>
                                    <?php echo abs($daysUntil); ?> day<?php echo abs($daysUntil) > 1 ? 's' : ''; ?> ago
                                <?php else: ?>
                                    In <?php echo $daysUntil; ?> day<?php echo $daysUntil > 1 ? 's' : ''; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="event-actions">
                        <span class="registered-button">
                            Registered
                        </span>
                    </div>
                </div>
                <?php 
                        endif;
                    endforeach; 
                else: 
                ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 2rem;">
                    <p>You haven't registered for any events yet. Browse upcoming events to find something interesting!</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Host Event CTA -->
        <?php if ($isLoggedIn): ?>
        <div class="cta-container">
            <h2 class="cta-title">Have knowledge to share?</h2>
            <p class="cta-description">
                Host your own event and connect with professionals who share your interests.
            </p>
            <a href="<?php echo URL_ROOT; ?>/dashboard/event_add" class="cta-button-primary">
                Host an Event
            </a>
        </div>
        <?php else: ?>
        <div class="cta-container">
            <h2 class="cta-title">Join our community to register for events</h2>
            <p class="cta-description">
                Sign up to participate in community events and connect with other professionals.
            </p>
            <div>
                <a href="<?php echo URL_ROOT; ?>/users/auth?action=register" class="cta-button-primary">
                    Sign Up Now
                </a>
                <a href="<?php echo URL_ROOT; ?>/users/auth?action=login" class="cta-button-secondary">
                    Log In
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Register Modal - For logged in users -->
<div id="register-modal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">Event Registration</h3>
            <button class="modal-close" onclick="closeModal('register-modal')">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="modal-event-info">
                <h4 id="modal-event-title" class="modal-event-title"></h4>
                <p id="modal-event-date" class="modal-event-date"></p>
                <p id="modal-event-description" class="modal-event-description"></p>
            </div>
            
            <form id="event-registration-form" action="<?php echo URL_ROOT; ?>/events/register" method="post">
                <input type="hidden" id="event-id" name="event_id">
                
                <div class="modal-form-group">
                    <label for="attendance-type" class="modal-label">How will you attend?</label>
                    <select id="attendance-type" name="attendance_type" class="modal-input">
                        <option value="virtual">Virtual</option>
                        <option value="in-person">In Person</option>
                    </select>
                </div>
                
                <div class="modal-form-group">
                    <label for="additional-notes" class="modal-label">Additional notes or questions</label>
                    <textarea id="additional-notes" name="notes" class="modal-input" rows="3" placeholder="Any questions or special requests..."></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="modal-button modal-button-secondary" onclick="closeModal('register-modal')">Cancel</button>
            <button class="modal-button modal-button-primary" onclick="submitRegistration()">Register</button>
        </div>
    </div>
</div>

<!-- Login Prompt Modal - For guests -->
<div id="login-prompt-modal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">Login Required</h3>
            <button class="modal-close" onclick="closeModal('login-prompt-modal')">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="modal-body" style="text-align: center;">
            <div style="margin-bottom: 1.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="#2c3e50" style="margin: 0 auto 1rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <h4 style="margin-bottom: 0.5rem; font-size: 1.125rem; font-weight: 600; color: #1a252f;">You need to be logged in</h4>
                <p class="modal-text">
                    Please log in to register for this event.
                </p>
            </div>
            <a href="<?php echo URL_ROOT; ?>/users/auth?action=login&redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" class="login-button">
                Log In Now
            </a>
            <p class="signup-link">
                Don't have an account? <a href="<?php echo URL_ROOT; ?>/users/auth?action=register">Sign Up</a>
            </p>
        </div>
    </div>
</div>

<!-- Registration Confirmation Modal -->
<div id="confirmation-modal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">Registration Confirmed</h3>
            <button class="modal-close" onclick="closeModal('confirmation-modal')">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="modal-body" style="text-align: center;">
            <div style="margin-bottom: 1.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="#2c3e50" style="margin: 0 auto 1rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <h4 style="margin-bottom: 0.5rem; font-size: 1.125rem; font-weight: 600; color: #1a252f;">You're registered!</h4>
                <p class="modal-text">
                    Your registration has been confirmed. We've sent you an email with all the details.
                </p>
            </div>
        </div>
        <div class="modal-footer">
            <button class="modal-button modal-button-primary" onclick="closeModal('confirmation-modal')">Done</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tab switching functionality
        window.showTab = function(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(function(tab) {
                tab.classList.add('hidden');
            });
            
            // Show the selected tab content
            document.getElementById(tabName + '-tab-content').classList.remove('hidden');
            
            // Update active tab styling
            document.querySelectorAll('.upcoming-tab, .calendar-tab').forEach(function(tab) {
                tab.classList.remove('active');
            });
            
            document.querySelector('.' + tabName + '-tab').classList.add('active');
        };
        
        // Function to show events for a specific day
        window.showDayEvents = function(day) {
            const monthNames = ["January", "February", "March", "April", "May", "June",
                               "July", "August", "September", "October", "November", "December"];
            const currentMonth = new Date().getMonth();
            const currentYear = new Date().getFullYear();
            
            // Update the selected date display
            document.getElementById('selected-date').textContent = `${monthNames[currentMonth]} ${day}, ${currentYear}`;
            
            // Get events data for the selected day
            const events = <?php echo json_encode($events); ?>;
            const registrations = <?php echo json_encode($data['registrations'] ?? []); ?>;
            const isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
            
            const selectedDayEvents = events.filter(event => {
                const eventDate = new Date(event.start_date);
                return eventDate.getDate() === day && eventDate.getMonth() === currentMonth;
            });
            
            const dayEventsContainer = document.getElementById('day-events');
            dayEventsContainer.innerHTML = '';
            
            if (selectedDayEvents.length > 0) {
                selectedDayEvents.forEach(event => {
                    const eventDate = new Date(event.start_date);
                    const endDate = new Date(event.end_date);
                    
                    // Calculate duration
                    const durationHours = Math.abs(endDate - eventDate) / 36e5;
                    const hours = Math.floor(durationHours);
                    const minutes = Math.round((durationHours - hours) * 60);
                    
                    let duration;
                    if (hours > 0 && minutes > 0) {
                        duration = `${hours} hour${hours > 1 ? 's' : ''} ${minutes} min`;
                    } else if (hours > 0) {
                        duration = `${hours} hour${hours > 1 ? 's' : ''}`;
                    } else {
                        duration = `${minutes} minutes`;
                    }
                    
                    // Check if user is registered
                    const isRegistered = registrations.some(reg => reg.event_id === event.id);
                    
                    const formattedTime = eventDate.toLocaleTimeString([], {hour: 'numeric', minute:'2-digit'});
                    const formattedDate = eventDate.toLocaleDateString('en-US', { 
                        weekday: 'short', 
                        month: 'short', 
                        day: 'numeric', 
                        year: 'numeric' 
                    });
                    
                    const eventHtml = `
                        <div class="day-event-card">
                            <h4 class="day-event-title">${escapeHtml(event.title)}</h4>
                            <p class="day-event-time">
                                ${formattedTime} • ${duration}
                            </p>
                            <p class="day-event-description">${escapeHtml(event.description.substring(0, 150))}${event.description.length > 150 ? '...' : ''}</p>
                            <div class="day-event-actions">
                                ${isRegistered ? 
                                    `<span class="registered-button">Registered</span>` : 
                                    `<a href="javascript:void(0)" onclick="handleEventRegistration(${event.id}, '${escapeHtml(event.title)}', '${formattedDate} ${formattedTime}', '${duration}', '${escapeHtml(event.description)}', ${event.is_virtual})" class="day-event-register">Register</a>`
                                }
                            </div>
                        </div>
                    `;
                    dayEventsContainer.innerHTML += eventHtml;
                });
            } else {
                dayEventsContainer.innerHTML = '<p class="no-events-message">No events scheduled for this day.</p>';
            }
        };
        
        // Helper function to escape HTML
        function escapeHtml(unsafe) {
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }
        
        // Event registration handler functions
        window.handleEventRegistration = function(eventId, title, date, duration, description, isVirtual) {
            // Check if user is logged in
            const isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
            
            if (isLoggedIn) {
                // Show registration modal for logged in users
                document.getElementById('modal-event-title').textContent = title;
                document.getElementById('modal-event-date').textContent = date + ' • ' + duration;
                document.getElementById('modal-event-description').textContent = description;
                document.getElementById('event-id').value = eventId;
                
                // Set default attendance type based on the event's virtual status
                const attendanceTypeSelect = document.getElementById('attendance-type');
                if (isVirtual) {
                    attendanceTypeSelect.value = 'virtual';
                } else {
                    attendanceTypeSelect.value = 'in-person';
                }
                
                openModal('register-modal');
            } else {
                // Show login prompt for guests
                openModal('login-prompt-modal');
            }
        };
        
        window.openModal = function(modalId) {
            document.getElementById(modalId).classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevent scrolling while modal is open
        };
        
        window.closeModal = function(modalId) {
            document.getElementById(modalId).classList.remove('active');
            document.body.style.overflow = ''; // Restore scrolling
        };
        
        window.submitRegistration = function() {
            // Submit the form
            document.getElementById('event-registration-form').submit();
        };
        
        // Close modals when clicking outside
        document.querySelectorAll('.modal-overlay').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
        });
        
        // Close modals with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal-overlay.active').forEach(modal => {
                    modal.classList.remove('active');
                    document.body.style.overflow = '';
                });
            }
        });
    });
</script>

<?php
// Include footer
require APPROOT . '/views/layouts/footer.php';
?> 