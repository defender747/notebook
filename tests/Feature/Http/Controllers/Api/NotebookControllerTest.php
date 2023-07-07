<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Note;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Tests\TestCase;

/**
 * Test Api methods (Check APP_ENV=testing)
 */
class NotebookControllerTest extends TestCase
{
    /** @var string */
    protected const ROUTE_API_NOTEBOOK = '/api/v1/notebook';
    /** @var string */
    protected const TABLE = 'notes';
    /** @var array */
    protected const NOTE_STRUCTURE = [
        'id',
        'full_name',
        'company',
        'phone',
        'email',
        'birth_date',

        'photo_url',
        'photo_must_deleted',

        'created_at',
        'updated_at',
    ];

    public function test_index(): void
    {
        $this->json(Request::METHOD_GET, self::ROUTE_API_NOTEBOOK)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'data' => [
                        '*' => self::NOTE_STRUCTURE
                    ]
                ]
            );
    }

    public function test_show(): void
    {
        $payload = Note::factory()->createOne()->getAttributes();

        $this->json(
            Request::METHOD_GET,
            sprintf('%s/%s', self::ROUTE_API_NOTEBOOK, $payload['id'])
        )
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'data' => self::NOTE_STRUCTURE
                ]
            );
    }

    public function test_store(): void
    {
        $note = Note::factory()->createOne()->getAttributes();
        unset($note['id']);
        $note['phone'] = $this->faker->phoneNumber;
        $note['email'] = $this->faker->email;

        $photo = UploadedFile::fake()->create(
            'photo.jpg',
            128
        );
        $payload = array_merge(
            $note,
            ['photo_file' => $photo]
        );

        $this->json(
            Request::METHOD_POST,
            self::ROUTE_API_NOTEBOOK,
            $payload
        )
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'data' => self::NOTE_STRUCTURE
                ]
            );
    }

    public function test_update_and_change_file(): void
    {
        $note = Note::factory()->createOne()->getAttributes();
        $photo = UploadedFile::fake()->create(
            'photo.jpg',
            128
        );
        $payload = array_merge(
            $note,
            ['photo_file' => $photo]
        );

        $this->json(
            Request::METHOD_POST,
            sprintf('%s/%s', self::ROUTE_API_NOTEBOOK, $payload['id']),
            $payload
        )
            ->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJsonStructure(
                [
                    'data' => self::NOTE_STRUCTURE
                ]
            );
    }

    public function test_update_and_delete_file(): void
    {
        $note = Note::factory()->createOne()->getAttributes();

        $payload = array_merge(
            $note,
            ['photo_must_deleted' => true],
            ['photo_file' => null]
        );

        $this->json(
            Request::METHOD_POST,
            sprintf('%s/%s', self::ROUTE_API_NOTEBOOK, $payload['id']),
            $payload
        )
            ->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJsonStructure(
                [
                    'data' => self::NOTE_STRUCTURE
                ]
            );

        $note['photo_uuid'] = null;
        $note['photo_name'] = null;
        $this->assertDatabaseHas(self::TABLE, $note);
    }

    public function test_destroy(): void
    {
        $payload = Note::factory()->createOne()->getAttributes();

        $this->json(
            Request::METHOD_DELETE,
            sprintf('%s/%s', self::ROUTE_API_NOTEBOOK, $payload['id'])
        )
            ->assertNoContent();

        $this->assertDatabaseMissing(self::TABLE, $payload);
    }
}
