<?php

function searchStudent($id, $conn) {
    $stmt = $conn->prepare("SELECT STUDENT_NAME FROM names WHERE NAME_ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo "Student Name: " . $row['STUDENT_NAME'] . "\n";
        $stmt->close();

        $stmt = $conn->prepare("SELECT COURSE_CODE, TEST_1, TEST_2, TEST_3, TEST_FINAL FROM course_grades WHERE STUDENT_ID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        echo "Courses:\n";
        while ($course = $result->fetch_assoc()) {
            echo "Course: " . $course['COURSE_CODE'] . ", Grades: " . $course['TEST_1'] . ", " . $course['TEST_2'] . ", " . $course['TEST_3'] . ", " . $course['TEST_FINAL'] . "\n";
        }
    } else {
        echo "Student not found.\n";
    }
    $stmt->close();
}

function deleteStudent($id, $conn) {
    $stmt = $conn->prepare("DELETE FROM names WHERE NAME_ID = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Student deleted from names.\n";
    } else {
        echo "Error deleting student from names: " . $stmt->error . "\n";
    }
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM course_grades WHERE STUDENT_ID = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Student deleted from course_grades.\n";
    } else {
        echo "Error deleting student from course_grades: " . $stmt->error . "\n";
    }
    $stmt->close();
}

function updateStudent($id, $newName, $courseCode, $grades, $conn) {
    $stmt = $conn->prepare("UPDATE names SET STUDENT_NAME = ? WHERE NAME_ID = ?");
    $stmt->bind_param("si", $newName, $id);
    if ($stmt->execute()) {
        echo "Student name updated.\n";
    } else {
        echo "Error updating student name: " . $stmt->error . "\n";
    }
    $stmt->close();

    $stmt = $conn->prepare("UPDATE course_grades SET COURSE_CODE = ?, TEST_1 = ?, TEST_2 = ?, TEST_3 = ?, TEST_FINAL = ? WHERE STUDENT_ID = ?");
    $stmt->bind_param("siiiis", $courseCode, $grades[0], $grades[1], $grades[2], $grades[3], $id);
    if ($stmt->execute()) {
        echo "Course and grades updated.\n";
    } else {
        echo "Error updating course and grades: " . $stmt->error . "\n";
    }
    $stmt->close();
}

?>
