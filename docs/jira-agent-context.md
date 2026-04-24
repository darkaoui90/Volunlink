# Volunlink Jira Planning Context

## Purpose
This document is a full project handoff brief for an AI agent whose task is to create or complete a Jira taskboard for Volunlink.

The goal is not to describe an imagined product. The goal is to describe the project as it currently exists in code, including:
- the business idea
- the implemented modules
- the partially implemented areas
- the missing areas
- the technical debt and architecture gaps
- the likely epic boundaries for Jira planning

Use this document as the source of truth for Jira planning unless the repository code clearly shows something newer.

## Project Summary
Volunlink is a Laravel-based volunteer management platform for Morocco 2030 / World Cup 2030 style event operations.

It is an operations and coordination system, not a social network.

The main idea is to help event organizers:
- collect volunteer registrations with useful operational profile data
- create missions or shifts
- attach missions to real physical sites
- assign volunteers to missions
- record attendance and lateness
- monitor mission staffing and attendance metrics on a dashboard
- notify admins when key events happen

## Product Vision
The intended system models real event operations around places such as:
- stadiums
- fan zones
- media centers
- transport hubs
- operations centers

The platform should support a realistic volunteer lifecycle:
1. Volunteers register and provide profile data.
2. Admin reviews and manages volunteers.
3. Admin creates sites where field operations happen.
4. Admin creates missions tied to those sites.
5. Admin assigns volunteers to missions.
6. Admin tracks attendance for assigned volunteers.
7. Admin monitors coverage and attendance through analytics and notifications.

## Current Stack
- PHP 8.2
- Laravel 11
- Laravel Breeze
- Blade templates
- Tailwind utility classes in views/layouts
- Alpine.js for light UI interactivity
- PHPUnit feature tests

## User Roles
### Admin
This is the most complete role in the current implementation.

Admin can currently:
- view the admin dashboard
- manage volunteers
- manage missions
- manage sites
- manage attendance
- view notifications
- manage personal profile and password

### Volunteer
Volunteer can currently:
- register through the public registration form
- access the volunteer dashboard
- view assigned mission details

### Coordinator
This role exists in the data model and routes, but is not fully implemented.

Current state:
- route exists
- dashboard route exists
- no rich workflow implemented

### Supervisor
This role exists in the data model and routes, but is not fully implemented.

Current state:
- route exists
- dashboard route exists
- no rich workflow implemented

## Core Domain Concepts
### User
Represents a system user with role-based behavior.

Important fields:
- `name`
- `email`
- `password`
- `role`
- `phone`
- `city`
- `languages`
- `skills`
- `availability`

Important behavior:
- first registered user becomes `admin`
- later registered users become `volunteer`
- `dashboardRouteName()` redirects users to the correct dashboard by role

### Site
Represents a physical event location.

Important fields:
- `name`
- `city`
- `type`
- `address`
- `capacity`
- `description`
- `latitude`
- `longitude`

Examples in seed data:
- Casablanca Stadium
- Rabat Media Center
- Marrakech Shuttle Hub
- Agadir Fan Zone
- Tangier Stadium
- Casablanca Operations Center

### Mission
Represents a volunteer mission, shift, or task.

Important fields:
- `title`
- `site_id`
- `description`
- `date`
- `start_time`
- `end_time`
- `location`
- `required_volunteers`

Important note:
- the project is transitioning from plain string-based mission `location` to normalized `site_id`
- `location` still exists as a fallback for compatibility

### Mission Assignment / Attendance
This is modeled through the `mission_user` pivot table.

Important pivot fields:
- `status`
- `late_minutes`

Current statuses used:
- `assigned`
- `present`
- `absent`
- `late`

## Current Business Flow
1. A guest registers.
2. If the system has no users yet, that first user becomes admin.
3. Any later registered user becomes volunteer.
4. Volunteers provide operational profile fields during registration.
5. Admin creates sites.
6. Admin creates missions linked to sites.
7. Admin assigns volunteers to missions.
8. Admin records attendance per volunteer per mission.
9. Admin monitors dashboard metrics.
10. Admin receives database notifications when:
- a new volunteer joins
- a mission is created

## Current Implemented Features
### Authentication and Access
Implemented:
- registration
- login
- logout
- email verification
- forgot password / reset password
- role-based dashboard redirection
- role middleware via `EnsureUserHasRole`

Relevant files:
- `routes/auth.php`
- `app/Http/Controllers/Auth/*`
- `app/Http/Middleware/EnsureUserHasRole.php`

### Volunteer Registration
Implemented registration fields:
- name
- email
- password
- phone
- city
- languages
- skills
- availability

Current behavior:
- first user becomes admin
- later users become volunteers

Relevant file:
- `app/Http/Controllers/Auth/RegisteredUserController.php`

### Admin Dashboard
Implemented dashboard metrics:
- total volunteers
- total missions
- mission fill rate
- mission coverage
- attendance rate
- recorded attendance count
- cities covered
- volunteers by city
- recent users
- recent volunteers

Relevant file:
- `app/Http/Controllers/Admin/AdminDashboardController.php`

### Volunteer Management
Implemented:
- volunteer listing
- search by name/email
- filter by city
- filter by skills
- edit volunteer
- delete volunteer

Important architecture note:
- this area still uses route closures inside `routes/web.php`
- it is functional, but not fully refactored into dedicated controllers

### Mission Management
Implemented:
- mission list
- mission create
- mission show
- mission edit
- mission delete
- mission filters
- mission assignment
- assignment conflict checks

Important details:
- missions are site-aware
- site selection is required when creating/editing missions
- `location` is still stored as fallback derived from site name

Relevant file:
- `app/Http/Controllers/Admin/MissionController.php`

### Site Management
Implemented:
- dynamic site model and database table
- site listing
- site create
- site edit
- site delete
- validation for site data
- delete protection if a site is already linked to missions

Relevant file:
- `app/Http/Controllers/Admin/SiteController.php`

### Attendance Management
Implemented:
- attendance index
- mission attendance detail page
- attendance update flow
- late hours/minutes input
- validation preventing invalid late values
- late values stored as total `late_minutes`

Relevant file:
- `app/Http/Controllers/Admin/AttendanceController.php`

### Profile Management
Implemented:
- default profile flow for non-admin users
- custom admin profile page
- admin can update:
  - name
  - email
  - phone
  - city
- admin can update password
- admin can delete account

Relevant files:
- `app/Http/Controllers/ProfileController.php`
- `app/Http/Requests/ProfileUpdateRequest.php`
- `resources/views/profile/admin-edit.blade.php`

### Notifications
Implemented:
- notifications table
- database notifications using Laravel notifications
- notification when a new volunteer registers
- notification when a mission is created
- bell dropdown in admin layout
- unread count badge
- mark-all-as-read action
- safe fallback if notifications table is missing
- bell remains clickable even when there are no notifications
- empty-state text was intentionally removed for the normal no-notification case

Important limitation:
- notifications are not real-time
- they are database-backed only
- no websocket or broadcast layer exists

Relevant files:
- `app/Notifications/VolunteerJoinedNotification.php`
- `app/Notifications/MissionCreatedNotification.php`
- `app/Http/Controllers/Admin/NotificationController.php`
- `resources/views/layouts/admin.blade.php`

### Volunteer Area
Implemented:
- volunteer dashboard
- volunteer mission detail page

Relevant route/controller:
- `routes/web.php`
- `app/Http/Controllers/Volunteer/MissionController.php`

## Current Route and Architecture Shape
### Main routing
Main application routes live in:
- `routes/web.php`
- `routes/auth.php`

### Architecture quality
The project is partly refactored and partly still pragmatic.

Current state:
- many core modules use dedicated controllers
- some admin volunteer CRUD still lives in route closures
- the project is not API-first
- the project is not SPA-based
- it is primarily server-rendered Blade

This matters for Jira because some tickets should be feature tickets, while others should be cleanup/refactor tickets.

## Data Model Snapshot
### User
Role constants:
- `admin`
- `coordinator`
- `supervisor`
- `volunteer`

Relationships:
- many-to-many with missions
- receives notifications

### Mission
Relationships:
- belongs to site
- many-to-many with users

Helpers:
- `site_name`
- `site_city`
- `display_location`
- `status_label`

### Site
Relationships:
- has many missions

### mission_user pivot
Represents:
- volunteer assignment
- attendance state
- late minutes tracking

## Database and Migrations
Important migrations already present:
- `0001_01_01_000000_create_users_table.php`
- `2026_04_09_000003_add_role_and_remove_status_from_users_table.php`
- `2026_04_11_000004_create_missions_table.php`
- `2026_04_12_000005_create_mission_user_table.php`
- `2026_04_13_155158_add_volunteer_fields_to_users_table.php`
- `2026_04_17_210000_add_late_minutes_to_mission_user_table.php`
- `2026_04_22_100000_create_sites_table.php`
- `2026_04_22_100100_add_site_id_to_missions_table.php`
- `2026_04_22_120000_create_notifications_table.php`

## Seeders
Important seeders:
- `SiteSeeder`
- `MissionSeeder`
- `MissionAssignmentSeeder`
- `DatabaseSeeder`

Current seeding purpose:
- preload realistic Morocco 2030 themed sites
- preload example missions
- preload assignments

## Current UI State
### Admin area
More customized and branded than the rest of the project.

Implemented/admin-oriented UI work includes:
- branded admin sidebar and header
- custom admin profile page
- custom logout button styling
- notification bell dropdown

### Non-admin area
More standard Laravel/Breeze in some places.

This means UI consistency is not fully complete across the whole system.

## Documentation Already Created
Diagram/code documentation already exists under `docs/`:
- `volunlink-class-diagram.mmd`
- `volunlink-domain-class-diagram.mmd`
- `volunlink-use-case-diagram.mmd`

There is also now:
- `jira-agent-context.md`

## Test Coverage and Quality State
The project currently has passing automated feature tests.

Latest confirmed state:
- `php artisan test` passes with `44` tests

Covered areas include:
- authentication
- registration and role assignment
- email verification
- password reset and password update
- profile behavior
- admin dashboard metrics
- site management
- attendance management
- volunteer dashboard
- notifications behavior

Notification tests cover:
- mission-created notification dispatch
- dashboard notification rendering
- mark all as read
- clickable empty dropdown behavior
- safe behavior when notifications table is missing

## Completed Work Summary
These areas are effectively implemented and likely belong under `Done` in Jira:
- authentication flow
- role-based registration behavior
- volunteer registration fields
- admin dashboard metrics
- mission CRUD
- mission assignment
- attendance management
- site management as a dynamic DB-backed feature
- custom admin profile page
- admin notification system
- UML diagram documentation
- automated tests for major modules

## Partially Implemented Areas
These areas are functional but incomplete, inconsistent, or architecturally unfinished.

They are good candidates for `In Progress`, `To Do`, or refactor tickets:
- coordinator workflow
- supervisor workflow
- volunteer management refactor out of route closures
- full normalization from legacy mission `location` to `site_id`
- broader UI consistency between admin and non-admin areas
- richer volunteer self-service features

## Missing or Future Features
These are not fully implemented and are good backlog candidates:
- full coordinator module
- full supervisor module
- real-time notifications
- reporting/export features
- more robust admin audit/history tracking
- volunteer-facing mission history or attendance history
- better permissions separation if coordinator/supervisor become active roles
- architecture cleanup and controller/service refactors
- stronger documentation and deployment artifacts if required by the project scope

## Technical Debt and Cleanup Opportunities
Important technical debt for Jira planning:
- some CRUD logic is still in route closures
- role support exists conceptually but only admin is mature
- notifications are not broadcast in real time
- `Mission.location` still coexists with `site_id`
- project architecture is mixed between clean controller patterns and quick route-level logic

## Likely Epic Candidates
These are the most natural Jira epic groupings based on the current codebase:
- User Authentication and Access Control
- Volunteer Registration and Profile Management
- Admin Dashboard and Analytics
- Volunteer Management
- Site Management
- Mission Management and Assignment
- Attendance Tracking
- Notifications and Alerts
- Role Expansion for Coordinator and Supervisor
- UI/UX Improvements
- Testing and Technical Refactoring
- Documentation and Project Delivery

## Guidance For Jira Creation
When converting this project into Jira work:
- do not recreate already completed core modules as new feature tickets
- create `Done` items for finished work
- create separate refactor/cleanup tickets where the feature exists but the implementation is not ideal
- distinguish clearly between:
  - delivered functionality
  - partially implemented functionality
  - future enhancements

Good Jira categories for this project:
- Epic
- Story
- Task
- Subtask
- Bug

Useful status grouping:
- Done
- In Progress
- To Do
- Backlog

## Suggested AI-Agent Instruction
If another AI agent is going to generate the Jira board, use this instruction:

> Use this document as the source of truth to create a Jira taskboard for Volunlink. Separate work into Done, In Progress, To Do, and Backlog. Do not duplicate implemented work as new feature tickets unless the new ticket is clearly a refactor, extension, cleanup, or enhancement. Keep the board realistic for a Laravel academic project and make the epics align with the real modules already present in the codebase.
