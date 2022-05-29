<?php

declare (strict_types=1);
namespace MonorepoBuilder20220529\Symplify\VendorPatches;

use MonorepoBuilder20220529\Nette\Utils\Strings;
use MonorepoBuilder20220529\Symplify\VendorPatches\ValueObject\OldAndNewFileInfo;
final class PatchFileFactory
{
    public function createPatchFilePath(\MonorepoBuilder20220529\Symplify\VendorPatches\ValueObject\OldAndNewFileInfo $oldAndNewFileInfo, string $vendorDirectory) : string
    {
        $newFileInfo = $oldAndNewFileInfo->getNewFileInfo();
        $inVendorRelativeFilePath = $newFileInfo->getRelativeFilePathFromDirectory($vendorDirectory);
        $relativeFilePathWithoutSuffix = \MonorepoBuilder20220529\Nette\Utils\Strings::lower($inVendorRelativeFilePath);
        $pathFileName = \MonorepoBuilder20220529\Nette\Utils\Strings::webalize($relativeFilePathWithoutSuffix) . '.patch';
        return 'patches' . \DIRECTORY_SEPARATOR . $pathFileName;
    }
}
