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
class SpacesRightSideFixer implements FixerInterface
{
    public function fix(\SplFileInfo $file, $content)
    {
        $tokens = Tokens::fromCode($content);
        $operator = array(T_IF, T_FOREACH);
        $singleop = array(',');
        foreach ($tokens as $index => $token) {
            if (!($token->isGivenKind($operator) || in_array($token->content, $singleop))) {
                continue;
            }

            $token->content = str_replace(' ', '', $token->content);

            $nextToken = $tokens[$index + 1];
            if ($nextToken->isWhitespace()) {
                $nextToken->content = ' ';
            } else {
                $token->content = $token->content.' ';
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
        return 'space_right_side';
    }

    public function getDescription()
    {
        return 'Space at right side.';
    }
}
