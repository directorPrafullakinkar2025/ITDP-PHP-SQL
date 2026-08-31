CREATE DATABASE IF NOT EXISTS shreeinfotechsof_itdp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE shreeinfotechsof_itdp;

-- Main Officer accounts are top-level administrators and are auto-approved.
-- UPDATE users SET approval_status='approved', status='active' WHERE role IN ('super_admin','admin') AND approval_status='pending';

CREATE TABLE users (id INT AUTO_INCREMENT PRIMARY KEY,name VARCHAR(100) NOT NULL,email VARCHAR(190) NOT NULL UNIQUE,password VARCHAR(255) NOT NULL,role ENUM('super_admin','admin','sub_officer','school_superintendent','hostel_superintendent','teacher','authorized_staff','staff') NOT NULL DEFAULT 'staff',institution_type ENUM('ashram_shala','wastigruh','') NOT NULL DEFAULT '',institution_code VARCHAR(100) NOT NULL DEFAULT '',status ENUM('active','blocked') NOT NULL DEFAULT 'active',approval_status ENUM('approved','pending','rejected') NOT NULL DEFAULT 'approved',approved_by INT NULL,approved_at DATETIME NULL,rejection_reason VARCHAR(500) DEFAULT '',last_login_at DATETIME NULL,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,INDEX(role),INDEX(institution_code));
CREATE TABLE records (id INT AUTO_INCREMENT PRIMARY KEY,title VARCHAR(160) NOT NULL,reference_number VARCHAR(100) NOT NULL UNIQUE,category ENUM('ashram_shala','wastigruh','infrastructure','training','service','policy','other') NOT NULL,institution_type VARCHAR(30) NOT NULL DEFAULT '',institution_code VARCHAR(100) NOT NULL DEFAULT '',institution_name VARCHAR(180) DEFAULT '',village VARCHAR(120) DEFAULT '',taluka VARCHAR(120) DEFAULT '',district VARCHAR(120) DEFAULT 'Yavatmal',status ENUM('pending','active','completed','archived') DEFAULT 'pending',description TEXT,owner_id INT NOT NULL,due_date DATE NULL,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,INDEX(institution_code),INDEX(owner_id));
CREATE TABLE work_tasks (id INT AUTO_INCREMENT PRIMARY KEY,title VARCHAR(180) NOT NULL,description TEXT,kind ENUM('instruction','office_work','circular') DEFAULT 'instruction',priority ENUM('low','normal','high','urgent') DEFAULT 'normal',department ENUM('all','ashram_shala','wastigruh','office') DEFAULT 'office',created_by INT NOT NULL,due_date DATE NULL,status ENUM('assigned','in_progress','submitted','verified','overdue','clarification_required','closed') DEFAULT 'assigned',progress_percent TINYINT UNSIGNED DEFAULT 0,warning_issued_at DATETIME NULL,clarification_requested_at DATETIME NULL,clarification TEXT,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,INDEX(status),INDEX(due_date));
CREATE TABLE task_assignees(task_id INT NOT NULL,user_id INT NOT NULL,PRIMARY KEY(task_id,user_id));
CREATE TABLE task_replies(id INT AUTO_INCREMENT PRIMARY KEY,task_id INT NOT NULL,user_id INT NOT NULL,message TEXT NOT NULL,progress_percent TINYINT UNSIGNED DEFAULT 0,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE task_authorities(task_id INT NOT NULL,user_id INT NOT NULL,status ENUM('pending','verified','rejected') DEFAULT 'pending',note VARCHAR(1000) DEFAULT '',checked_at DATETIME NULL,PRIMARY KEY(task_id,user_id));
CREATE TABLE operational_reports(id INT AUTO_INCREMENT PRIMARY KEY,report_date DATE NOT NULL,institution_type VARCHAR(30) NOT NULL,institution_code VARCHAR(100) NOT NULL,total_students INT DEFAULT 0,present INT DEFAULT 0,absent INT DEFAULT 0,work_status ENUM('not_started','in_progress','completed','issue') DEFAULT 'not_started',work_summary TEXT,submitted_by INT NOT NULL,verified_by INT NULL,verification_status ENUM('pending','verified','rejected') DEFAULT 'pending',verification_note VARCHAR(1000) DEFAULT '',created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,INDEX(institution_code),INDEX(report_date));
CREATE TABLE alerts(id INT AUTO_INCREMENT PRIMARY KEY,type ENUM('attendance','daily_work','task_overdue','approval','verification','system') NOT NULL,title VARCHAR(180) NOT NULL,message TEXT NOT NULL,severity ENUM('info','warning','urgent') DEFAULT 'info',institution_type VARCHAR(30) DEFAULT '',institution_code VARCHAR(100) DEFAULT '',created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE officer_orders(id INT AUTO_INCREMENT PRIMARY KEY,title VARCHAR(180) NOT NULL,message TEXT NOT NULL,priority ENUM('normal','high','urgent') DEFAULT 'normal',target_type ENUM('all','ashram_shala','wastigruh') DEFAULT 'all',target_institution_code VARCHAR(100) DEFAULT '',due_date DATE NULL,status ENUM('open','closed') DEFAULT 'open',created_by INT NOT NULL,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE user_files(id INT AUTO_INCREMENT PRIMARY KEY,original_name VARCHAR(240) NOT NULL,stored_name VARCHAR(240) NOT NULL UNIQUE,mime_type VARCHAR(150),size BIGINT NOT NULL,category ENUM('document','image','archive','spreadsheet','presentation','other') DEFAULT 'document',description VARCHAR(500) DEFAULT '',owner_id INT NOT NULL,institution_type VARCHAR(30) DEFAULT '',institution_code VARCHAR(100) DEFAULT '',created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,INDEX(owner_id));
CREATE TABLE letter_templates(id INT AUTO_INCREMENT PRIMARY KEY,name VARCHAR(180) NOT NULL,category ENUM('official_letter','notesheet','information_table','circular') DEFAULT 'official_letter',original_name VARCHAR(240) NOT NULL,stored_name VARCHAR(240) NOT NULL,mime_type VARCHAR(150) NOT NULL,file_size BIGINT DEFAULT 0,placeholders TEXT,uploaded_by INT NOT NULL,is_active TINYINT(1) DEFAULT 1,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE work_notes(id INT AUTO_INCREMENT PRIMARY KEY,title VARCHAR(180) NOT NULL,body TEXT,plan_type ENUM('today','next_day','next_week','general') DEFAULT 'today',status ENUM('planned','in_progress','completed','cancelled') DEFAULT 'planned',due_date DATE NULL,owner_id INT NOT NULL,created_by INT NOT NULL,linked_task INT NULL,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE work_reports(id INT AUTO_INCREMENT PRIMARY KEY,period ENUM('hourly','daily','monthly','yearly') NOT NULL,period_start DATETIME NOT NULL,period_end DATETIME NOT NULL,user_id INT NOT NULL,generated_by INT NOT NULL,assigned INT DEFAULT 0,completed INT DEFAULT 0,in_progress INT DEFAULT 0,overdue INT DEFAULT 0,replies INT DEFAULT 0,notes_planned INT DEFAULT 0,notes_completed INT DEFAULT 0,files_uploaded INT DEFAULT 0,entries TEXT,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE activities(id INT AUTO_INCREMENT PRIMARY KEY,user_id INT NOT NULL,action ENUM('login','created','updated','deleted','status_changed') NOT NULL,module VARCHAR(60) NOT NULL,record_id INT NULL,summary VARCHAR(240) NOT NULL,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,INDEX(created_at));

-- Admin login: admin@itdp.com / Admin#123
INSERT INTO users(name,email,password,role,institution_type,institution_code,status,approval_status) VALUES ('ITDP Administrator','admin@itdp.com','$2y$12$CkCSTOV8.xi8SRHtXOy9he0Ct839z4HI9n8Y1EDXjpRvKUa3WuJsW','admin','','','active','approved');
-- Demo institution accounts, same password Admin#123
INSERT INTO users(name,email,password,role,institution_type,institution_code,status,approval_status) VALUES
('Ashram Superintendent','ashram@itdp.com','$2y$12$CkCSTOV8.xi8SRHtXOy9he0Ct839z4HI9n8Y1EDXjpRvKUa3WuJsW','school_superintendent','ashram_shala','ASHRAM001','active','approved'),
('Wastigruh Superintendent','wasti@itdp.com','$2y$12$CkCSTOV8.xi8SRHtXOy9he0Ct839z4HI9n8Y1EDXjpRvKUa3WuJsW','hostel_superintendent','wastigruh','WASTI001','active','approved');

-- ITDP Phase 2: Ashram Shala student attendance + Wastigruh daily monitoring
CREATE TABLE IF NOT EXISTS students (
 id INT AUTO_INCREMENT PRIMARY KEY, student_name VARCHAR(160) NOT NULL, student_uid VARCHAR(100) NOT NULL UNIQUE,
 admission_no VARCHAR(100) DEFAULT '', dob DATE NULL, class_name VARCHAR(50) NOT NULL, gender VARCHAR(20) DEFAULT 'Male',
 guardian_name VARCHAR(160) DEFAULT '', guardian_contact VARCHAR(30) DEFAULT '', address TEXT,
 institution_type VARCHAR(30) NOT NULL DEFAULT 'ashram_shala', institution_code VARCHAR(100) NOT NULL,
 status ENUM('active','inactive','transferred') DEFAULT 'active', created_by INT NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 INDEX(institution_code), INDEX(class_name)
);
CREATE TABLE IF NOT EXISTS student_attendance (
 id INT AUTO_INCREMENT PRIMARY KEY, student_id INT NOT NULL, attendance_date DATE NOT NULL,
 status ENUM('present','absent','leave') NOT NULL DEFAULT 'present', remarks VARCHAR(500) DEFAULT '', marked_by INT NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, UNIQUE KEY uq_student_date(student_id,attendance_date), INDEX(attendance_date)
);
CREATE TABLE IF NOT EXISTS wastigruh_daily (
 id INT AUTO_INCREMENT PRIMARY KEY, report_date DATE NOT NULL, institution_type VARCHAR(30) NOT NULL DEFAULT 'wastigruh', institution_code VARCHAR(100) NOT NULL,
 total_residents INT DEFAULT 0, present INT DEFAULT 0, absent INT DEFAULT 0, new_admission INT DEFAULT 0,
 breakfast_status ENUM('served','partial','not_served','issue') DEFAULT 'served', lunch_status ENUM('served','partial','not_served','issue') DEFAULT 'served',
 snacks_status ENUM('served','partial','not_served','issue') DEFAULT 'served', dinner_status ENUM('served','partial','not_served','issue') DEFAULT 'served', meal_remarks TEXT,
 uniform_status ENUM('good','shortage','damaged','repair') DEFAULT 'good', daily_clothes_status ENUM('good','shortage','damaged','repair') DEFAULT 'good', shoes_status ENUM('good','shortage','damaged','repair') DEFAULT 'good', blanket_status ENUM('good','shortage','damaged','repair') DEFAULT 'good', mattress_status ENUM('good','shortage','damaged','repair') DEFAULT 'good',
 sports_equipment_status ENUM('good','partial','shortage','issue') DEFAULT 'good', sports_activity_status ENUM('good','partial','shortage','issue') DEFAULT 'good', study_room_status ENUM('good','partial','shortage','issue') DEFAULT 'good', library_status ENUM('good','partial','shortage','issue') DEFAULT 'good',
 water_status ENUM('good','partial','issue','shortage') DEFAULT 'good', electricity_status ENUM('good','partial','issue','shortage') DEFAULT 'good', toilet_hygiene_status ENUM('good','partial','issue','shortage') DEFAULT 'good', cleanliness_status ENUM('good','partial','issue','shortage') DEFAULT 'good', kitchen_status ENUM('good','partial','issue','shortage') DEFAULT 'good', first_aid_status ENUM('good','partial','issue','shortage') DEFAULT 'good', fire_safety_status ENUM('good','partial','issue','shortage') DEFAULT 'good', security_status ENUM('good','partial','issue','shortage') DEFAULT 'good',
 complaints TEXT, action_required TEXT, remarks TEXT, submitted_by INT NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 UNIQUE KEY uq_wasti_date(institution_code,report_date), INDEX(report_date)
);
CREATE TABLE IF NOT EXISTS wastigruh_inventory (
 id INT AUTO_INCREMENT PRIMARY KEY, institution_type VARCHAR(30) NOT NULL DEFAULT 'wastigruh', institution_code VARCHAR(100) NOT NULL,
 category VARCHAR(60) NOT NULL, item_name VARCHAR(160) NOT NULL, total_qty INT DEFAULT 0, good_qty INT DEFAULT 0, damaged_qty INT DEFAULT 0, shortage_qty INT DEFAULT 0,
 remarks VARCHAR(500) DEFAULT '', updated_by INT NOT NULL, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 UNIQUE KEY uq_inventory_item(institution_code,category,item_name), INDEX(category)
);
