<?php

/*
 * This file is part of the PHP CS utility.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Symfony\CS\Fixer;

use Symfony\CS\FixerInterface;
use Symfony\CS\Token;
use Symfony\CS\Tokens;
/**
 * @author Lyndon Wong
 */
class NamspaceDeclarationFixer implements FixerInterface
{
    public function fix(\SplFileInfo $file, $content)
    {
        $tokens = Tokens::fromCode($content);

        foreach ($tokens as $index => $token) {
            if (T_NAMESPACE !== $token->id) {
                continue;
            }

            $nextIndex = null;
            $nextToken = $tokens->getNextTokenOfKind($index, array(';'), $nextIndex);

            // no correct end of code - break
            if (null === $nextToken) {
                break;
            }

            // new statement with () - nothing to do
            if (';' === $nextToken->content) {
                if (strlen($tokens[$nextIndex + 1]->content)<=1) {
                    $tokens[$nextIndex]->content.="\n";
                    // $tokens->insertAt($nextIndex + 2, array(new Token("\n")));
                }
            }

        }

        return $tokens->generateCode();
    }

    public function getLevel()
    {
        return FixerInterface::PSR2_LEVEL;
    }

    public function getPriority()
    {
        return 0;
    }

    public function supports(\SplFileInfo $file)
    {
        return 'php' === pathinfo($file->getFilename(), PATHINFO_EXTENSION);
    }

    public function getName()
    {
        return 'new_with_namespace_declaration';
    }

    public function getDescription()
    {
        return 'There must be one blank line after the namespace declaration.';
    }
}
