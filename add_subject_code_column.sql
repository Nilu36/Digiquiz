-- Run this in phpMyAdmin only if the subjects table does not yet have subject_code.
USE digiquiz;

ALTER TABLE subjects
ADD COLUMN subject_code VARCHAR(50) NOT NULL DEFAULT '' AFTER name;

-- After running this, open DigiQuiz Admin > Subjects and add the correct
-- subject code for each subject. The student pages will display that code.
