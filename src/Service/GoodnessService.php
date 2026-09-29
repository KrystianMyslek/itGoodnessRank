<?php

namespace App\Service;

use App\Model\GoodnessStatusEnum;
use App\Entity\Goodness;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class GoodnessService
{
	public function __construct(
		private EntityManagerInterface $manager,
		private FileUploaderService $fileUploader
	) {
	}

	public function create(Goodness $goodness, $status = GoodnessStatusEnum::Active): void
	{
		$goodness->setStatus($status);
		$goodness->setCreatedAt(new \DateTimeImmutable('now'));

		$this->manager->persist($goodness);
		$this->manager->flush();
	}

	public function createProposal(Goodness $goodness): void
	{
		$this->create($goodness, GoodnessStatusEnum::Proposal);
	}

	public function update(Goodness $goodness): void
	{
		if ($goodness->getStatus() === GoodnessStatusEnum::Proposal) {
			$goodness->setStatus(GoodnessStatusEnum::Active);
		}

		$goodness->setCreatedAt(new \DateTimeImmutable('now'));

		$this->manager->flush();
	}

	public function setIcon(Goodness $goodness, UploadedFile $iconFile): Goodness
	{
		$iconFilename = $this->fileUploader->upload($iconFile, 'goodness_icon');

		$goodness->setIcon($iconFilename);

		return $goodness;
	}

	public function delete(Goodness $goodness): void
	{
		$goodness->setStatus(GoodnessStatusEnum::Deleted);

		$this->manager->flush();
	}

	public function approve(Goodness $goodness): void
	{
		$goodness->setStatus(GoodnessStatusEnum::Active);

		$this->manager->flush();
	}
}