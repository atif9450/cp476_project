function searchStudentById($id, $conn) {
    $stmt = $conn->prepare("SELECT name FROM NameTable WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo "Student Name: " . $row['name'] . "\n";
        $stmt->close();

        $stmt = $conn->prepare("SELECT course, grade1, grade2, grade3, grade4 FROM CourseTable WHERE student_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        echo "Courses:\n";
        while ($course = $result->fetch_assoc()) {
            echo "Course: " . $course['course'] . ", Grades: " . $course['grade1'] . ", " . $course['grade2'] . ", " . $course['grade3'] . ", " . $course['grade4'] . "\n";
        }
    } else {
        echo "Student not found.\n";
    }
    $stmt->close();
}

function deleteStudent($id, $conn) {
    $stmt = $conn->prepare("DELETE FROM NameTable WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Student deleted from NameTable.\n";
    } else {
        echo "Error deleting student from NameTable: " . $stmt->error . "\n";
    }

    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM CourseTable WHERE student_id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Student deleted from CourseTable.\n";
    } else {
        echo "Error deleting student from CourseTable: " . $stmt->error . "\n";
    }

    $stmt->close();
}

function updateStudent($id, $newName, $course, $grades, $conn) {
    // Update the student's name
    $stmt = $conn->prepare("UPDATE NameTable SET name = ? WHERE id = ?");
    $stmt->bind_param("si", $newName, $id);
    if ($stmt->execute()) {
        echo "Student name updated.\n";
    } else {
        echo "Error updating student name: " . $stmt->error . "\n";
    }
    $stmt->close();

    // Add a new course with grades for the student
    $stmt = $conn->prepare("INSERT INTO CourseTable (student_id, course, grade1, grade2, grade3, grade4) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isiiii", $id, $course, $grades[0], $grades[1], $grades[2], $grades[3]);
    if ($stmt->execute()) {
        echo "Course and grades added.\n";
    } else {
        echo "Error adding course and grades: " . $stmt->error . "\n";
    }
    $stmt->close();
}