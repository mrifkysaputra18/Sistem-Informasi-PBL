    // ========================================
    // AUTHORIZATION HELPER METHODS
    // ========================================

    /**
     * Get all class IDs where this dosen is assigned (PBL or Mata Kuliah)
     * Used for filtering classes in dropdowns and authorization checks
     * 
     * @return \Illuminate\Support\Collection
     */
    public function getAssignedClassRoomIds()
    {
        if (!$this->isDosen()) {
            return collect([]);
        }

        // Get from Dosen PBL
        $pblClassIds = $this->kelasPblAktif->pluck('id');
        
        // Get from Dosen Mata Kuliah
        $matkulClassIds = \Illuminate\Support\Facades\DB::table('kelas_mata_kuliah')
            ->where('dosen_sebelum_uts_id', $this->id)
            ->orWhere('dosen_sesudah_uts_id', $this->id)
            ->distinct()
            ->pluck('class_room_id');
        
        return $pblClassIds->merge($matkulClassIds)->unique();
    }

    /**
     * Check if dosen can access a specific class
     * Returns true if user is assigned as Dosen PBL or Dosen Mata Kuliah for the class
     * 
     * @param int $classRoomId
     * @return bool
     */
    public function canAccessClassRoom(int $classRoomId): bool
    {
        if ($this->isAdmin()) {
            return true; // Admin can access all classes
        }

        if (!$this->isDosen()) {
            return false;
        }

        return $this->getAssignedClassRoomIds()->contains($classRoomId);
    }

    /**
     * Check if dosen is Dosen PBL for a specific class
     * 
     * @param int $classRoomId
     * @return bool
     */
    public function isDosenPblFor(int $classRoomId): bool
    {
        if (!$this->isDosen()) {
            return false;
        }

        return $this->isDosenPblDi($classRoomId);
    }

    /**
     * Check if dosen can input nilai (grades) for a specific class
     * Based on Dosen PBL or Dosen Mata Kuliah assignment
     * 
     * @param int $classRoomId
     * @return bool
     */
    public function canInputNilaiFor(int $classRoomId): bool
    {
        return $this->canAccessClassRoom($classRoomId);
    }
}
