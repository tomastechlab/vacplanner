<?php declare(strict_types=1);

namespace App\Enum;

use App\Entity\Comment;
use App\Entity\Event;
use App\Entity\Gallery;
use App\Entity\Image;
use App\Entity\ItemList;
use App\Entity\Item;
use App\Entity\Poll;
use App\Entity\PollOption;

enum Relations : string {
    case GENERAL = 'general';
    case EVENT = Event::class;
    case POLL = Poll::class;
    case POLLOPTION = PollOption::class;
    case LISTITEM = Item::class;
    case LIST = ItemList::class;
    case COMMENT = Comment::class;
    case GALLERY = Gallery::class;
    case IMAGE = Image::class;
}
