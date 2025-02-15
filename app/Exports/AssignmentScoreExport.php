<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\Assignment;

class AssignmentScoreExport implements FromArray, WithHeadings, WithEvents
{
    protected $assignment;

    public function __construct(
        protected string $academicYear,
        protected string $classroom,
        protected string $semester,
        protected string $subject,
        protected mixed $answerAssignment,
    ) {}

    public function headings(): array
    {
        return [];
    }

    public function array(): array
    {
        $assignment = $this->assignment;
        $subject = $this->subject;
        $classroom = $this->classroom;
        $academicYear = $this->academicYear;
        $semester = $this->semester;
        
        $data = [
            ['Tahun Ajaran: ' . $this->academicYear],
            ['Kelas: ' . $this->classroom],
            ['Mata Pelajaran: ' . $this->subject],
            ['Semester: ' . $this->semester],
            [],
            ['NIS', 'Nama', 'Nilai', 'Tanggal'],
        ];
        
        foreach ($this->answerAssignment as $answer) {
            $student = $answer->studentClassroom->student;
            $user = $student->user;
            $score = optional($answer->score)->point ?? 0;
            $date = $answer->created_at->format('d/m/Y H:i');
            
            $data[] = [
                $student->nis,
                $user->name,
                $score,
                $date,
            ];
        }

        return $data;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:A4')->applyFromArray([
                    'font' => ['bold' => true]
                ]);
                $event->sheet->getDelegate()->getStyle('A6:D6')->applyFromArray([
                    'font' => ['bold' => true]
                ]);
            },
        ];
    }
}
