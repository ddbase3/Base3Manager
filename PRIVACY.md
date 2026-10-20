# Base3Manager privacy and data processing

This source-based inventory is not a deployment-specific privacy notice or a
claim of regulatory compliance.

`Base3Manager` discovers installed plugin metadata, module definitions,
navigation entries and other configuration files. The manager service does not
persist user identities, grants, groups or feature audiences. Administration
screens are assembled by the owning host plugins; authorization decisions and
writes remain the responsibility of the respective backend services.

The wider Manager deployment may process authenticated administrator identity,
configuration content and remote authorization metadata through host sessions,
configured services and the XRM connection. Actual storage, log and backup
retention, recipients, transfers, security controls and deletion procedures
must be established from the deployed services; the supplied code does not
verify these operational details. Protect administration routes and enforce
rights on every server-side operation.
