<?php

declare (strict_types=1);
namespace MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Cleaner;

use MonorepoBuilder20210705\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
final class RequireRequireDevDuplicateCleaner
{
    /**
     * @param array<string, mixed> $requireDev
     * @return array<string, mixed>
     */
    public function unsetPackageFromRequire(\MonorepoBuilder20210705\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $mainComposerJson, array $requireDev) : array
    {
        // give require priority
        $requirePackageNames = $mainComposerJson->getRequirePackageNames();
        foreach ($requirePackageNames as $requirePackageName) {
            if (!isset($requireDev[$requirePackageName])) {
                continue;
            }
            unset($requireDev[$requirePackageName]);
        }
        return $requireDev;
    }
}
