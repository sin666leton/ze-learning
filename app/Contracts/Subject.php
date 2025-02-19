<?php

namespace App\Contracts;

interface Subject
{
    /**
     * Cari mata pelajaran berdasarkan id
     * 
     * @param int $id
     * @return array{
     *  id: int,
     *  semester_id: int,
     *  name: string,
     *  kkm: int,
     *  semester: array{
     *      id: int,
     *      name: string
     *  },
     *  classroom: array{
     *      id: int,
     *      name: string
     *  }
     * }
     */
    public function find(int $id): array;

    /**
     * Cari mata pelajaran berdasarkan id kelas
     * #sementara
     * 
     * @param int $id Classroom id
     * @return array
     */
    public function getByClassroom(int $id): array;

    /**
     * Cari mata pelajaran berdasarkan id dengan menyertakan
     * semua semester dalam kelas/tahun ajaran pada mata pelajaran
     * tersebut
     * 
     * @param int $id
     * @return array{
     *  id: int,
     *  classroom_id: int,
     *  semester_id: int,
     *  name: string,
     *  kkm: int,
     *  semester: array{
     *      id: int,
     *      name: string
     *  },
     *  classroom: array{
     *      id: int,
     *      academic_year_id: int,
     *      name: string,
     *      academic_year: array{
     *          id: int,
     *          semesters: array<int, array{
     *              id: int,
     *              academic_year_id: int,
     *              name: string
     *          }>
     *      }
     *  }
     * }
     */
    public function findSubjectIncludeSemesterList(int $id): array;

    /**
     * Tambah mata pelajaran
     * 
     * @param int $semesterID
     * @param int $classroomID
     * @param string $name
     * @param int $kkm
     * @return array{
     *  id: int,
     *  classroom_id: int,
     *  semester_id: int
     * }
     */
    public function create(int $semesterID, int $classroomID, string $name, int $kkm = 70): array;

    /**
     * Perbarui mata pelajaran
     * 
     * @param int $semesterID
     * @param int $id
     * @param string $name
     * @param int $kkm
     * @return array{
     *  classroom_id: int,
     *  semester_id: int
     * }
     */
    public function update(int $semesterID, int $id, string $name, int $kkm = 70): array;

    /**
     * Hapus mata pelajaran
     * 
     * @param int $id
     * @return bool|null
     */
    public function delete(int $id): bool|null;
}
