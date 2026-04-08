# Model Conventions

A Model class file should be organized into sections with the following order:

- A using traits section.
- **Table structure** section defining the Model's table and the Model's attributes as constants. This is intended for more convenient typing in the IDE and easier maintenance and updating of table's columns.
- **Configuration** section defining the Model's casts, guarded, fillable, and hidden attributes.
- **Mutators & Accessors** section.
- **Relationship** section.
