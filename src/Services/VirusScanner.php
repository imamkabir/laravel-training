<?php
namespace Services;
class VirusScanner {
    // returns ['risk_level'=>'clean'|'infected','message'=>'...']
    public function scan(string $filepath): array {
        // prefer clamdscan if installed (daemon), fallback to clamscan
        $cmd1 = "clamdscan --no-summary " . escapeshellarg($filepath) . " 2>&1";
        $cmd2 = "clamscan --no-summary " . escapeshellarg($filepath) . " 2>&1";
        $out=[]; $rc=1;
        exec($cmd1, $out, $rc);
        if($rc > 1){ // clamdscan failed, try clamscan
            $out=[]; $rc=1;
            exec($cmd2, $out, $rc);
        }
        // rc codes: 0 -> no virus, 1 -> virus found, >1 -> error
        if($rc === 0) {
            return ['risk_level'=>'clean','message'=>'No threats found'];
        } elseif($rc === 1) {
            return ['risk_level'=>'infected','message'=>'Virus signatures matched'];
        } else {
            return ['risk_level'=>'unknown','message'=>'Scanner error: '.implode("\n",$out)];
        }
    }
}
