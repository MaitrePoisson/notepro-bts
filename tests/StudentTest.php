<?php

namespace App\Tests\Entity;

use App\Entity\Evaluation;
use App\Entity\Grade;
use App\Entity\Student;
use App\Entity\Subject;
use PHPUnit\Framework\TestCase;

class StudentTest extends TestCase
{
    public function testGradesAreFilteredByPublishDate(): void
    {
        $student = new Student();

        $subject = $this->createMock(Subject::class);
        $subject->method('getLabel')->willReturn('Maths');

        $evalPublished = $this->createMock(Evaluation::class);
        $evalPublished->method('getSubject')->willReturn($subject);
        $evalPublished->method('getDatePublish')->willReturn(new \DateTime('-1 day'));

        $gradePublished = $this->createMock(Grade::class);
        $gradePublished->method('getEvaluation')->willReturn($evalPublished);

        $student->addGrade($gradePublished);

        $evalFuture = $this->createMock(Evaluation::class);
        $evalFuture->method('getSubject')->willReturn($subject);
        $evalFuture->method('getDatePublish')->willReturn(new \DateTime('+1 day'));

        $gradeFuture = $this->createMock(Grade::class);
        $gradeFuture->method('getEvaluation')->willReturn($evalFuture);

        $student->addGrade($gradeFuture);

        $result = $student->getGradesBySubject();

        $this->assertArrayHasKey('Maths', $result);
        $this->assertCount(1, $result['Maths']);
        $this->assertSame($gradePublished, $result['Maths'][0]);
    }

    public function testGetAveragesBySubject(): void
    {
        $student = new Student();

        $subjectMaths = $this->createMock(Subject::class);
        $subjectMaths->method('getLabel')->willReturn('Maths');

        $subjectAnglais = $this->createMock(Subject::class);
        $subjectAnglais->method('getLabel')->willReturn('Anglais');

        $eval1 = $this->createMock(Evaluation::class);
        $eval1->method('getSubject')->willReturn($subjectMaths);
        $eval1->method('getBareme')->willReturn(20);
        $eval1->method('getDatePublish')->willReturn(new \DateTime('-1 day'));

        $grade1 = $this->createMock(Grade::class);
        $grade1->method('getEvaluation')->willReturn($eval1);
        $grade1->method('getGrade')->willReturn('15');
        $grade1->method('isPresent')->willReturn(true);

        $eval2 = $this->createMock(Evaluation::class);
        $eval2->method('getSubject')->willReturn($subjectMaths);
        $eval2->method('getBareme')->willReturn(40);
        $eval2->method('getDatePublish')->willReturn(new \DateTime('-1 day'));

        $grade2 = $this->createMock(Grade::class);
        $grade2->method('getEvaluation')->willReturn($eval2);
        $grade2->method('getGrade')->willReturn('10');
        $grade2->method('isPresent')->willReturn(true);

        $evalOther = $this->createMock(Evaluation::class);
        $evalOther->method('getSubject')->willReturn($subjectAnglais);
        $evalOther->method('getBareme')->willReturn(20);
        $evalOther->method('getDatePublish')->willReturn(new \DateTime('-1 day'));

        $gradeOther = $this->createMock(Grade::class);
        $gradeOther->method('getEvaluation')->willReturn($evalOther);
        $gradeOther->method('getGrade')->willReturn('18');
        $gradeOther->method('isPresent')->willReturn(true);

        $student->addGrade($grade1);
        $student->addGrade($grade2);
        $student->addGrade($gradeOther);

        $averages = $student->getAveragesBySubject();

        $this->assertEqualsWithDelta(8.33, $averages['Maths'], 0.01);
        $this->assertEquals(18.0, $averages['Anglais']);
    }
}
