<?php

namespace App\Tests\Controller;

use App\DataFixtures\BookFixtures;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class BookControllerTest extends WebTestCase
{
    protected $kernelDir = '/app';

    /** @var ReferenceRepository */
    private $fixtures;

    public function setUp()
    {
        $this->fixtures = $this->loadFixtures(
            [
                BookFixtures::class,
            ]
        )->getReferenceRepository();
    }

    public function testGetFeatured()
    {
        $client = $this->makeClient(true);
        $client->request('GET', '/books/highlighted');

        $this->assertStatusCode(200, $client);
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(0, $data['offset']);
        $this->assertEquals(50, $data['limit']);
        $this->assertEquals(101, $data['total']);
        $books = $data['books'];
        $this->assertEquals(50, count($books));

        // Test First and Last = 50th book data...
        $this->assertEquals(
            [
                'id' => $this->fixtures->getReference('book1')->getId(),
                'title' => 'Ikofurioico Oyabizak',
                'description' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cyrenaici quidem non recusant; Quae cum essent dicta, discessimus. At coluit ipse amicitias. De illis, cum volemus. Si longus, levis dictata sunt. Duo Reges: constructio interrete. Nulla erit controversia. Eadem nunc mea adversum te oratio est. \n\nSed ad illum redeo. Itaque hic ipse iam pridem est reiectus; Praeclare hoc quidem. Nihil sane. Quis non odit sordidos, vanos, leves, futtiles? Praeclare hoc quidem. \n\nHuius ego nunc auctoritatem sequens idem faciam. Qualem igitur hominem natura inchoavit? Compensabatur, inquit, cum summis doloribus laetitia. Memini vero, inquam; \n\nCur deinde Metrodori liberos commendas? Ratio quidem vestra sic cogit. Ut id aliis narrare gestiant? \n\nNihil illinc huc pervenit. Tubulo putas dicere? Restatis igitur vos; Perge porro; Paria sunt igitur. Quare conare, quaeso. \n\nBeatum, inquit. Ita nemo beato beatior. Honesta oratio, Socratica, Platonis etiam. Cur post Tarentum ad Archytam? Frater et T. \n\nIta prorsus, inquam; Cur post Tarentum ad Archytam? Haec dicuntur inconstantissime. Maximus dolor, inquit, brevis est. \n\nSi quicquam extra virtutem habeatur in bonis. Invidiosum nomen est, infame, suspectum. Polycratem Samium felicem appellabant. Ut pulsi recurrant? Etiam beatissimum? Sed quot homines, tot sententiae; \n\nApparet statim, quae sint officia, quae actiones. Hunc vos beatum; Nihilo magis. Omnia peccata paria dicitis. Quo tandem modo? \n\nQuis negat? Sed hoc sane concedamus. Estne, quaeso, inquam, sitienti in bibendo voluptas? An hoc usque quaque, aliter in vita? Si id dicis, vicimus. Quod ea non occurrentia fingunt, vincunt Aristonem; \n\n",
                'cover_url' => 'http://placehold.it/248x340/d0d5da/336699&text=1',
                'isbn' => '31250388',
                'publisher' => [
                    'id' => $this->fixtures->getReference('publisher10')->getId(),
                    'name' => 'Epehujupoeiyuoeb',
                ],
                'author' => [
                    'id' => $this->fixtures->getReference('author23')->getId(),
                    'first_name' => 'Idejuxol',
                    'last_name' => 'Uvefobau',
                ],
            ],
            $books[0]
        );
        $this->assertEquals(
            [
                'id' => $this->fixtures->getReference('book280')->getId(),
                'title' => 'Uaelakopa Efoxuaavemof Afadigeb',
                'description' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quo modo autem philosophus loquitur? Is es profecto tu. Quorum altera prosunt, nocent altera. Duo Reges: constructio interrete. Quis istum dolorem timet? Contemnit enim disserendi elegantiam, confuse loquitur. \n\nIlle incendat? Contineo me ab exemplis. Egone quaeris, inquit, quid sentiam? Sedulo, inquam, faciam. \n\nSatis est ad hoc responsum. Quo tandem modo? Proclivi currit oratio. Ut id aliis narrare gestiant? At iam decimum annum in spelunca iacet. Sed nimis multa. \n\nQuare conare, quaeso. Immo alio genere; Contineo me ab exemplis. Non est ista, inquam, Piso, magna dissensio. \n\nScaevolam M. At enim hic etiam dolore. Quis enim redargueret? Hoc tu nunc in illo probas. \n\nAt ille pellit, qui permulcet sensum voluptate. Aliter enim explicari, quod quaeritur, non potest. Cur iustitia laudatur? Hoc tu nunc in illo probas. \n\nCollatio igitur ista te nihil iuvat. Non laboro, inquit, de nomine. \n\nNullus est igitur cuiusquam dies natalis. Apparet statim, quae sint officia, quae actiones. \n\nEstne, quaeso, inquam, sitienti in bibendo voluptas? Utilitatis causa amicitia est quaesita. Illud non continuo, ut aeque incontentae. Vide, quantum, inquam, fallare, Torquate. Nunc haec primum fortasse audientis servire debemus. \n\nEquidem e Cn. Id mihi magnum videtur. Hic nihil fuit, quod quaereremus. \n\n",
                'cover_url' => 'http://placehold.it/248x340/d0d5da/336699&text=280',
                'isbn' => '89004439',
                'publisher' => [
                    'id' => $this->fixtures->getReference('publisher20')->getId(),
                    'name' => 'Emezavesuzodelix',
                ],
                'author' => [
                    'id' => $this->fixtures->getReference('author94')->getId(),
                    'first_name' => 'Atuoomou',
                    'last_name' => 'Ajefaiof',
                ],
            ],
            $books[49]
        );
    }

    //public function testGetFeaturedWorksWithLimit()
    public function testGetFeaturedFailsIfOffsetNotNumeric()
    {
        $client = $this->makeClient(true);
        $client->request('GET', '/books/highlighted/bad_offset');

        $this->assertStatusCode(200, $client);
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(
            [
                'error' => [
                    'code' => 400,
                    'message' => 'Bad Request: invalid value specified for `offset`'
                ]
            ],
            $response
        );
    }

    public function testGetFeaturedFailsIfLimitNotNumeric()
    {
        $client = $this->makeClient(true);
        $client->request('GET', '/books/highlighted/5/bad_limit');

        $this->assertStatusCode(200, $client);
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(
            [
                'error' => [
                    'code' => 400,
                    'message' => 'Bad Request: invalid value specified for `limit`'
                ]
            ],
            $response
        );
    }

    public function testGetFeaturedFailsIfOffsetNegative()
    {
        $client = $this->makeClient(true);
        $client->request('GET', '/books/highlighted/-1');

        $this->assertStatusCode(200, $client);
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(
            [
                'error' => [
                    'code' => 400,
                    'message' => 'Bad Request: Invalid value specified for `offset`. Minimum required value is 0.'
                ]
            ],
            $response
        );
    }

    public function testGetFeaturedFailsIfLimitAboveMaximum()
    {
        $client = $this->makeClient(true);
        $client->request('GET', '/books/highlighted/5/101');

        $this->assertStatusCode(200, $client);
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(
            [
                'error' => [
                    'code' => 400,
                    'message' => 'Bad Request: Invalid value specified for `limit`. Maximum allowed value is 100.'
                ]
            ],
            $response
        );
    }

    public function testGetOneWorks()
    {
        $id5 = $this->fixtures->getReference('book5')->getId();

        $client = $this->makeClient(true);
        $client->request('GET', '/books/' . $id5);

        $this->assertStatusCode(200, $client);
        $book = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(
            [
                'id' => $this->fixtures->getReference('book5')->getId(),
                'title' => 'Aeuaooetojur Oraeaceoau Ivuuegozoxoh',
                'description' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Poterat autem inpune; Duo Reges: constructio interrete. Ego vero isti, inquam, permitto. Ille incendat? Ostendit pedes et pectus. Simus igitur contenti his. Certe, nisi voluptatem tanti aestimaretis. \n\nScrupulum, inquam, abeunti; An eiusdem modi? Age, inquies, ista parva sunt. Non igitur bene. Confecta res esset. Sin aliud quid voles, postea. Respondeat totidem verbis. Aliter homines, aliter philosophos loqui putas oportere? \n\nPolycratem Samium felicem appellabant. Pugnant Stoici cum Peripateticis. Minime vero istorum quidem, inquit. Quod vestri non item. Si quicquam extra virtutem habeatur in bonis. Ut aliquid scire se gaudeant? Age, inquies, ista parva sunt. Et ille ridens: Video, inquit, quid agas; \n\nQuorum altera prosunt, nocent altera. At certe gravius. \n\nHaeret in salebra. Restatis igitur vos; Negare non possum. Non est igitur voluptas bonum. \n\nQuid Zeno? Verum hoc idem saepe faciamus. Primum quid tu dicis breve? Si quicquam extra virtutem habeatur in bonis. \n\nA mene tu? Ostendit pedes et pectus. \n\nInvidiosum nomen est, infame, suspectum. Cave putes quicquam esse verius. \n\nDat enim intervalla et relaxat. Occultum facinus esse potuerit, gaudebit; Quo modo autem philosophus loquitur? Nulla erit controversia. Nihil ad rem! Ne sit sane; \n\nNihilo magis. Nos commodius agimus. Huius ego nunc auctoritatem sequens idem faciam. Satis est ad hoc responsum. \n\n",
                'cover_url' => 'http://placehold.it/248x340/d0d5da/336699&text=5',
                'isbn' => '34165410',
                'publisher' => [
                    'id' => $this->fixtures->getReference('publisher18')->getId(),
                    'name' => 'Ioixacibudusigim',
                ],
                'author' => [
                    'id' => $this->fixtures->getReference('author88')->getId(),
                    'first_name' => 'Iriuuuei',
                    'last_name' => 'Aioferas',
                ],
            ],
            $book
        );
    }

    public function testGetOneFailsIfIdNotNumeric()
    {
        $client = $this->makeClient(true);
        $client->request('GET', '/books/bad_id');

        $this->assertStatusCode(200, $client);
        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(
            [
                'error' => [
                    'code' => 400,
                    'message' => 'Bad Request: invalid value specified for `bookId`'
                ]
            ],
            $response
        );
    }

    public function testGetOneFailsIfNotFound()
    {
        $client = $this->makeClient(true);
        $client->request('GET', '/books/-2');

        $this->assertStatusCode(200, $client);
        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(
            [
                'error' => [
                    'code' => 400,
                    'message' => 'Bad Request: book not found'
                ]
            ],
            $response
        );
    }
}
