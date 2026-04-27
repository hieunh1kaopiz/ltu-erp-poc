-- Migration: tạo bảng deploy_schedules
-- Lưu thông tin lịch deploy đã đặt từ ERP UI, đồng bộ với Semaphore Schedules
-- qua semaphore_schedule_id.

CREATE TABLE IF NOT EXISTS deploy_schedules (
    id                       BIGINT PRIMARY KEY AUTO_INCREMENT,
    semaphore_schedule_id    INT          NOT NULL,
    instance_id              VARCHAR(64)  NOT NULL,
    deploy_tag               VARCHAR(32)  NOT NULL,
    scheduled_at             DATETIME     NOT NULL,
    created_by               VARCHAR(128) NOT NULL,
    reason                   TEXT,
    status ENUM('pending','reminded','running','cancelled','completed')
                                          NOT NULL DEFAULT 'pending',
    created_at               DATETIME     DEFAULT CURRENT_TIMESTAMP,
    updated_at               DATETIME     DEFAULT CURRENT_TIMESTAMP
                                          ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status_scheduled (status, scheduled_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
