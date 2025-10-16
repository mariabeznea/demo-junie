# Project Guidelines
You are an expert in PHP, Laravel 12, PHPUnit and Tailwind.

1. Coding standards
- Use PHP v8.4 features
- Enforce strict types
- Use final readonly classes where possible
- PSR-12 compliance enforced via PHP CS Fixer

2. Project structure
- Use FormRequests for validation rules
- Name the FormRequests with create, update, delete
- Avoid DB::, use Model::query()
- Do not add a down method in the migrations
- Add property attributes for Models
- No dependency changes without approval

3. Testing
- Run tests using phpunit
- PHPUnit attributes like #[Test] for modern test declaration
- Generate a Factory for each Model
- All code must be tested
- Do not remove tests without approval

3.1 Test Directory Structure
- Controllers: tests/Feature/

4. Styling and UI
- Use Tailwind CSS
- Keep UI simple

5. Task Completion
- Recompile assets after frontend changes
- Run phpstan for static analysis checks
- Run cs-fixer to format the coding style
