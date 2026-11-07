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

## Global launcher integration

The Manager page now emits locally served ClientStack launcher assets only when
the active XRM authority explicitly permits its feature. The floating launcher
loads chatbot HTML from a separate Manager endpoint, which repeats exactly
the same host authorization decision. The active host identity is evaluated by
the XRM-backed authorization service; ManagerChatbot does not read the local
user identity independently. The launcher persists selected
window preferences in browser storage according to the shared ClientStack
implementation. Chat conversations and any upstream processing follow the
existing Chatbot, agent runtime and configured provider services. Do not infer
retention, transfer destinations, or deletion from the launcher alone.

The Manager integrates plugin-owned translations through the module registry;
those language files contain static UI text, not user records. Layout changes
do not change access rights or persistence. Keyboard accessibility, focus
management, and screen reader announcements follow the shared ClientStack
launcher, which requires browser-level verification in the deployed Manager.
