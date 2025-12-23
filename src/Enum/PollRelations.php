<?php declare(strict_types=1);

namespace App\Enum;

use App\Entity\Comment;
use App\Entity\Event;
use App\Entity\Gallery;
use App\Entity\Image;
use App\Entity\ItemList;
use App\Entity\ItemListItem;
use App\Entity\Poll;
use App\Entity\PollOption;

enum Relations : string {
    case GENERAL = 'general';
    case EVENT = Event::class;
    case POLL = Poll::class;
    case POLLOPTION = PollOption::class;
    case LISTITEM = ItemListItem::class;
    case LIST = ItemList::class;
    case COMMENT = Comment::class;
    case GALLERY = Gallery::class;
    case IMAGE = Image::class;
}
