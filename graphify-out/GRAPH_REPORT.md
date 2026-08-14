# Graph Report - C:\laragon\www\compro  (2026-08-14)

## Corpus Check
- Corpus is ~43,019 words - fits in a single context window. You may not need a graph.

## Summary
- 605 nodes · 846 edges · 110 communities (94 shown, 16 thin omitted)
- Extraction: 96% EXTRACTED · 4% INFERRED · 0% AMBIGUOUS · INFERRED: 34 edges (avg confidence: 0.83)
- Token cost: 0 input · 0 output

## Community Hubs (Navigation)
- Community 0
- Community 1
- Community 2
- Community 3
- Community 4
- Community 5
- Community 6
- Community 7
- Community 8
- Community 9
- Community 10
- Community 11
- Community 12
- Community 13
- Community 14
- Community 15
- Community 16
- Community 17
- Community 18
- Community 19
- Community 20
- Community 21
- Community 22
- Community 23
- Community 24
- Community 25
- Community 26
- Community 27
- Community 28
- Community 29
- Community 41
- Community 42
- Community 43
- Community 44
- Community 45
- Community 46
- Community 47
- Community 48
- Community 49
- Community 50
- Community 51
- Community 52
- Community 63

## God Nodes (most connected - your core abstractions)
1. `Team` - 48 edges
2. `User` - 32 edges
3. `Portfolio` - 18 edges
4. `Laravel Best Practices` - 14 edges
5. `TeamInvitation` - 13 edges
6. `scripts` - 13 edges
7. `TeamPolicy` - 12 edges
8. `require-dev` - 12 edges
9. `Testimonial` - 11 edges
10. `Controller` - 10 edges

## Surprising Connections (you probably didn't know these)
- `Laravel Logo` --conceptually_related_to--> `Laravel Boost Guidelines`  [INFERRED]
  public/favicon.svg → AGENTS.md
- `Test Enforcement` --conceptually_related_to--> `Testing Best Practices`  [INFERRED]
  AGENTS.md → .agents/skills/laravel-best-practices/rules/testing.md
- `Continuous Integration Tests` --implements--> `Test Enforcement`  [INFERRED]
  .github/workflows/tests.yml → AGENTS.md
- `Evidence-Based Conventions` --semantically_similar_to--> `Consistency First`  [INFERRED] [semantically similar]
  .agents/skills/infer-conventions/SKILL.md → .agents/skills/laravel-best-practices/SKILL.md
- `Advanced Query Patterns` --conceptually_related_to--> `Database Performance Best Practices`  [INFERRED]
  .agents/skills/laravel-best-practices/rules/advanced-queries.md → .agents/skills/laravel-best-practices/rules/db-performance.md

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **Laravel Data Access Performance** — _agents_skills_laravel_best_practices_rules_advanced_queries_advanced_query_patterns, _agents_skills_laravel_best_practices_rules_db_performance_database_performance_best_practices, _agents_skills_laravel_best_practices_rules_eloquent_eloquent_best_practices, _agents_skills_laravel_best_practices_rules_collections_collection_best_practices, _agents_skills_laravel_best_practices_rules_migrations_migration_best_practices [INFERRED 0.85]
- **Laravel Asynchronous Integrations** — _agents_skills_laravel_best_practices_rules_events_notifications_events_notifications_best_practices, _agents_skills_laravel_best_practices_rules_http_client_http_client_best_practices, _agents_skills_laravel_best_practices_rules_mail_mail_best_practices, _agents_skills_laravel_best_practices_rules_error_handling_error_handling_best_practices [INFERRED 0.75]
- **Laravel Application Quality Practices** — _agents_skills_laravel_best_practices_rules_security_security_best_practices, _agents_skills_laravel_best_practices_rules_testing_testing_best_practices, _agents_skills_laravel_best_practices_rules_validation_validation_and_forms_best_practices, _agents_skills_laravel_best_practices_rules_style_laravel_conventions_and_style [INFERRED 0.85]
- **Reactive Frontend Development Stack** — _agents_skills_livewire_development_skill_livewire_development, _agents_skills_livewire_development_reference_javascript_hooks_interceptor_system, _agents_skills_tailwindcss_development_skill_tailwind_css_development [INFERRED 0.75]
- **Automated Quality Pipeline** — _github_dependabot_dependency_update_policy, _github_workflows_tests_continuous_integration, agents_test_enforcement, _agents_skills_pest_testing_skill_pest_testing_4 [INFERRED 0.85]

## Communities (110 total, 16 thin omitted)

### Community 0 - "Community 0"
Cohesion: 0.09
Nodes (15): PortfolioController, ServiceController, SiteSettingController, TestimonialController, Controller, PublicController, Portfolio, Service (+7 more)

### Community 1 - "Community 1"
Cohesion: 0.06
Nodes (23): assignable(), hasPermission(), isAtLeast(), label(), level(), permissions(), TeamPermission, static (+15 more)

### Community 2 - "Community 2"
Cohesion: 0.08
Nodes (26): belongsToTeam(), currentTeam(), fallbackTeam(), hasTeamPermission(), isCurrentTeam(), ownedTeams(), ownsTeam(), personalTeam() (+18 more)

### Community 3 - "Community 3"
Cohesion: 0.09
Nodes (16): currentTeam(), redirectPathForCurrentTeam(), LoginResponse, PasskeyLoginResponse, RegisterResponse, TwoFactorLoginResponse, VerifyEmailResponse, AppServiceProvider (+8 more)

### Community 4 - "Community 4"
Cohesion: 0.06
Nodes (37): scripts, ci:check, dev, lint, lint:check, post-autoload-dump, post-create-project-cmd, post-root-package-install (+29 more)

### Community 5 - "Community 5"
Cohesion: 0.07
Nodes (28): concurrently, @laravel/multiplex, @laravel/passkeys, laravel-vite-plugin, lightningcss-linux-x64-gnu, dependencies, concurrently, @laravel/passkeys (+20 more)

### Community 6 - "Community 6"
Cohesion: 0.11
Nodes (11): CreateNewUser, ResetUserPassword, CreateTeam, emailRules(), nameRules(), profileRules(), AdminUserSeeder, DatabaseSeeder (+3 more)

### Community 7 - "Community 7"
Cohesion: 0.11
Nodes (9): Membership, TeamInvitation, TeamInvitation, Illuminate\Bus\Queueable, Illuminate\Contracts\Queue\ShouldQueue, Illuminate\Database\Eloquent\Relations\BelongsTo, Illuminate\Database\Eloquent\Relations\Pivot, Illuminate\Notifications\Messages\MailMessage (+1 more)

### Community 8 - "Community 8"
Cohesion: 0.14
Nodes (20): Architecture Convention Dimensions, Laravel Convention Detection Checklist, Evidence-Based Conventions, Infer Conventions, Path-Scoped Rules, Advanced Query Patterns, Architecture Best Practices, Blade and View Best Practices (+12 more)

### Community 9 - "Community 9"
Cohesion: 0.11
Nodes (20): Factory-Driven Testing, Testing Best Practices, Livewire Interceptor System, Livewire 4 JavaScript Integration, Livewire 4 Component Formats, Livewire Development, Reactive Component Practices, Browser and Smoke Testing (+12 more)

### Community 10 - "Community 10"
Cohesion: 0.16
Nodes (6): EnsureTeamMembership, SetTeamUrlDefaults, TeamName, UniqueTeamInvitation, Closure, Illuminate\Contracts\Validation\ValidationRule

### Community 11 - "Community 11"
Cohesion: 0.17
Nodes (12): require-dev, fakerphp/faker, larastan/larastan, laravel/boost, laravel/pail, laravel/pao, laravel/pint, laravel/sail (+4 more)

### Community 12 - "Community 12"
Cohesion: 0.22
Nodes (9): require, laravel/chisel, laravel/fortify, laravel/framework, laravel/tinker, livewire/blaze, livewire/flux, livewire/livewire (+1 more)

### Community 13 - "Community 13"
Cohesion: 0.25
Nodes (8): Implicit Route Model Binding, Routing and Controller Best Practices, Thin Controllers, Authorization and Secret Management, Input and Output Protection, Security Best Practices, Form Request Validation, Validation and Forms Best Practices

### Community 14 - "Community 14"
Cohesion: 0.25
Nodes (7): closeDeleteModal, confirmDelete({{ $passkey[, deletePasskey, disable, $dispatch(, pages, partials.settings-heading

### Community 15 - "Community 15"
Cohesion: 0.25
Nodes (7): description, license, minimum-stability, name, prefer-stable, $schema, type

### Community 16 - "Community 16"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 19 - "Community 19"
Cohesion: 0.40
Nodes (5): Duplicate Job Prevention, Queue and Job Best Practices, Resilient Job Execution, Overlap and Concurrency Control, Task Scheduling Best Practices

### Community 20 - "Community 20"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 21 - "Community 21"
Cohesion: 0.40
Nodes (5): extra, laravel, post-create-project, dont-discover, installer

### Community 22 - "Community 22"
Cohesion: 0.67
Nodes (4): Laravel Fortify, Passkey Authentication, SPA Authentication, Two-Factor Authentication

### Community 23 - "Community 23"
Cohesion: 0.50
Nodes (3): confirmTwoFactor, resetVerification, showVerificationIfNecessary

### Community 24 - "Community 24"
Cohesion: 0.50
Nodes (3): create-team-modal, partials.head, team-switcher

### Community 25 - "Community 25"
Cohesion: 0.50
Nodes (3): create-team-modal, partials.head, team-switcher

### Community 26 - "Community 26"
Cohesion: 0.50
Nodes (3): pages, partials.settings-heading, updateMember({{ $member[

### Community 28 - "Community 28"
Cohesion: 0.67
Nodes (3): autoload-dev, psr-4, Tests\\

### Community 29 - "Community 29"
Cohesion: 0.67
Nodes (3): keywords, framework, laravel

## Knowledge Gaps
- **124 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+119 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **16 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Team` connect `Community 2` to `Community 0`, `Community 1`, `Community 3`, `Community 6`, `Community 10`?**
  _High betweenness centrality (0.067) - this node is a cross-community bridge._
- **Why does `User` connect `Community 2` to `Community 0`, `Community 1`, `Community 10`, `Community 6`?**
  _High betweenness centrality (0.034) - this node is a cross-community bridge._
- **Why does `TeamInvitation` connect `Community 7` to `Community 0`, `Community 1`?**
  _High betweenness centrality (0.025) - this node is a cross-community bridge._
- **Are the 2 inferred relationships involving `Team` (e.g. with `.definition()` and `.configure()`) actually correct?**
  _`Team` has 2 INFERRED edges - model-reasoned connections that need verification._
- **Are the 2 inferred relationships involving `User` (e.g. with `.definition()` and `.run()`) actually correct?**
  _`User` has 2 INFERRED edges - model-reasoned connections that need verification._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _124 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Community 0` be split into smaller, more focused modules?**
  _Cohesion score 0.09013914095583787 - nodes in this community are weakly interconnected._