-- Migration cố tình lỗi để test rollback
CREATE TABLE this_will_fail (
    id INT NOT NULL,
    bad_column INVALID_TYPE NOT NULL
);
