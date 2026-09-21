<?php

namespace App\Enums\Activity;

enum ActivityAction: string
{
    case Created = 'created';
    case Updated = 'updated';
    case Deleted = 'deleted';

    case Published = 'published';
    case Unpublished = 'unpublished';

    case SubmittedForReview = 'submitted_for_review';
    case Approved = 'approved';
    case Rejected = 'rejected';

    case MemberAdded = 'member_added';
    case MemberRemoved = 'member_removed';

    case InvitationSent = 'invitation_sent';
    case InvitationAccepted = 'invitation_accepted';

    case FindingCreated = 'finding_created';
    case EvidenceAdded = 'evidence_added';

    case VersionCreated = 'version_created';
    case VersionPublished = 'version_published';
    case VersionSuperseded = 'version_superseded';
}
