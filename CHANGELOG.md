# Changelog

## 2.0.0 - Unreleased

- Remove the VAPM runtime dependency from `Counter`.
- Change `Counter::getFolderInfo()` to return its statistics synchronously. Callers migrating from 1.x must remove their VAPM `Async` handling.
- Add `Counter::getFolderInfoFromDirectory()` for code that does not need to pass a plugin instance.
- Reject a missing scan directory with `InvalidArgumentException`.
