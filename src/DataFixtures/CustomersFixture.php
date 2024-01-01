<?php
	
	namespace App\DataFixtures;
	
	use App\Factory\CustomerFactory;
	use Doctrine\Bundle\FixturesBundle\Fixture;
	use Doctrine\Persistence\ObjectManager;
	
	class CustomersFixture extends Fixture
	{
		public function load(ObjectManager $manager): void
		{
			
			CustomerFactory::new()->createMany(100);
			$manager->flush();
		}
	}
