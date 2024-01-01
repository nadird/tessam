<?php

namespace App\Factory;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Symfony\Component\Uid\Uuid;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Customer>
 *
 * @method        Customer|Proxy                     create(array|callable $attributes = [])
 * @method static Customer|Proxy                     createOne(array $attributes = [])
 * @method static Customer|Proxy                     find(object|array|mixed $criteria)
 * @method static Customer|Proxy                     findOrCreate(array $attributes)
 * @method static Customer|Proxy                     first(string $sortedField = 'id')
 * @method static Customer|Proxy                     last(string $sortedField = 'id')
 * @method static Customer|Proxy                     random(array $attributes = [])
 * @method static Customer|Proxy                     randomOrCreate(array $attributes = [])
 * @method static CustomerRepository|RepositoryProxy repository()
 * @method static Customer[]|Proxy[]                 all()
 * @method static Customer[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Customer[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Customer[]|Proxy[]                 findBy(array $attributes)
 * @method static Customer[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Customer[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class CustomerFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

	private const LAST_NAMES = ['CHORFA', 'BACHA', 'IHADADENE', 'KHERFI', 'YAHI', 'BENCHOUK', 'BOUZIANE', 'ALLOUACHE', 'SID', 'HADJ SADOK', 'ALIOUD', 'HAMMOUTENE', 'MOHAMMEDI', 'SEDRATI', 'ALI MEHIDI', 'GUENOUCHE', 'CHALLAB', 'OURTILANE', 'EL KETTAS', 'OUSSALEM', 'AIT ALDJET', 'TAZEROUT', 'HADJ SAHRAOUI', 'ACHEK-YOUCEF', 'GHLAMALLAH', 'DJEBARRA', 'MEZIANI', 'ABDELAZIZ', 'LONGUAR', 'DERROUIS', 'OULD KACI', 'CHAOUANE', 'HADJ HENNI', 'MIMOUN', 'MAHTOUT', 'AMOR', 'HABI', 'ZOUANE', 'BOUDJELLAL', 'ABA', 'KAOUAH', 'AMROUN', 'AHNIA', 'HADJEBAR', 'OUIDIR', 'AIT BOUZIAD', 'ACHOUR', 'SEGHIER', 'DAOUD', 'BENTOBBAL', 'BISKER', 'MALEK', 'BENHARKAT', 'ABDELOUAHAB', 'BOUKTAB', 'GHESSAB', 'BELMEHDI', 'BENRADJA', 'SEKFALI', 'MEDJDOUB', 'BRIHI', 'SARRI', 'BOULELLI', 'DEGHDAK', 'SENOUSSAOUI', 'DAHMANI', 'TAMALOUST', 'METTOUCHI', 'AMRANE', 'YAHYAOUI', 'GHANEM', 'BELAMRI', 'AMRI', 'YAHMI', 'MECHAI', 'MAHFOUF', 'ASKRI', 'DJOUIMAA', 'ARIOUAT', 'BELKHODJA', 'MOHAND KACI', 'BESSEDIK', 'BESSAIH', 'ABDELOUAHAB', 'SAIDI', 'AIT HAMMOUDI', 'DJEGHRI', 'DJOUAMA', 'OUMEZIANE', 'MANSOURI', 'YAHIA', 'SID OTMANE', 'SALAH', 'BENMAHMOUD', 'REZZOUK', 'SEBBANE', 'GHERABI', 'REFFAD', 'HALATA'];
	private const FIRST_NAMES = ['Abdelkader', 'Abdelkrim', 'Abdelmadjid', 'Abdelmalek', 'Abdelouahab', 'Abdelouahed', 'Abdelouahid,', 'Nassima','Chemseddine','Sofiane Reda','Nabil','Manel','Karim','Sarah','Rayad','Sarah','Louiza','Nazim','Khaoula','Mahmoud','Yacine','Meriem','Lotfi','Mohamed','Ghiles','Mansour El Mahdi','Yasmine','Toufik','Ryma','Houssem Eddine','Hamama','Mouhssine-Cherif','Djamel','Chafik','Maria','Nesrine','Azzedine','Riad','Amine','Mebarek Abderraouf','Ryma','Mehdi Nacim','Salima','Mehdi Rafik','Dalila','Abdelatif','Lyes','Essaid','Lyes Mounir','Adel','Riad','Fehrat','Lamine','Lydia','Mustapha kamel','Hocine','Nassim','Djamel','Chahrazede','Khedidja','Feriel','Chahinez','Sihem','Lamia','Nadir','Abdelkrim','Ikram','Badreddine','Fella','Imene','Karim','Islam','Abd El Illah Kamel Eddine','Amel Baya','Ahmed Djebrail','Nawel','Lylia','Samir','Billel','Nourddine','Amina','Karima','Samia','Mounia','Salima','Saloua','Nadia','Walid','Radia','Moussa','Fatma','Amel','Ourdia','Anis','Lylia Meriem','Safia','Faysal','Fatiha','Amina','Afef','Abdelhadi','Abdesslam'];
	
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
		
		$uuid = Uuid::v4();
		$dateAdd = self::faker()->dateTime();
		$dateUpdate = clone $dateAdd;
		$dateUpdate->modify('+1 day');
		
        return [
            'dateAdd' => $dateAdd,
            'dateUpdated' => $dateUpdate,
            'email' => self::faker()->email(),
            'firstname' => self::faker()->randomElement(self::FIRST_NAMES),
            'isActive' => self::faker()->boolean(),
            'isDeleted' => self::faker()->boolean(),
            'isVerified' => self::faker()->boolean(),
            'lastname' => self::faker()->randomElement(self::LAST_NAMES),
            'phone' => self::faker()->phoneNumber(),
            'roles' => [],
            'uuid' => $uuid,
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Customer $customer): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Customer::class;
    }
}
