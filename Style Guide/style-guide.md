# QuantConnect Documentation Style Guide 

Welcome to the QuantConnect documentation style guide for contributors. We've created this guide to make contributions easier to read for users and enforce a standard tone across the documentation. As we rewrite the documentation for "v2" we'll rely on this guide to help guide the way!


## Write in active voice.
Active voice makes the performer of the action (usually the user) the subject of the sentence. Active-voice sentences are more direct and easier to understand than passive-voice sentences.
The exception to this rule is when active voice sounds like you're blaming the user, the performer of action is unknown, or you want to de-emphasize the performer of the action and emphasize the object on which the action is performed, or when using active voice makes the sentence wordy or awkward.
| Use | Avoid |
|--|--|
| After you install the software, start the computer. | After the software has been installed, the computer can be started. | 
| Click **OK** to save the configuration. | The configuration is saved when the **OK** button is clicked. |
| After you install the software, start the computer. | After the software has been installed, the computer can be started. |
| Create a server. | A server is created by you. |
| QuantConnect services solve your business problems. | Your business problems are solved by QuantConnect services. |  

## Use present tense.
Users read content to help them perform tasks or to gather information. These activities occur in the users' present time, so the present tense is appropriate in most content. Additionally, sentences that use the present tense are easier to read than sentences that use past or future tense.

Use future tense only when you need to emphasize that something occurs later, from the users' perspective.

| Use | Avoid |
|--|--|
| The product **prompts** you to verify the deletion. | The product **will prompt** you to verify the deletion. |
| After you log in, your account **begins** the verification process. | After you log in, your account **will then begin** the verification process. |
| The migrations will begin in June 2017 | N/A Future tense is appropriate in this example. |

Tip: To easily find and remove instances of future tense, search for _will_.

### Write to the user by using second person and imperative mood.
Users are more engaged with content when it talks to them directly. You talk to users directly by using _second person_, addressing the user as _you_. Second person also promotes a friendly tone.

 - Use second person with the imperative mood (in which the subject _you_ is understood) and active voice to eliminate wordiness and confusion about who or what initiates an action, especially in procedural steps.
 - Use second person to avoid the use of gender-specific, third-person pronouns such as  _he_,  _she_,  _his_, and  _hers_. If you must use third person, use the pronouns  _they_  and  _their_, but ensure that the noun that the pronoun refers to is plural.
 - Use first-person plural pronouns (_we_,  _our_) judiciously. These pronouns emphasize the writer or QuantConnect rather than the user, so before you use them, consider whether second person or imperative mood would be more "user friendly." However, use  _we recommend_  rather than  _it's recommended_  or  _QuantConnect recommends_. Also, you can use  _we_  in the place of  _QuantConnect_  if necessary.
 - Use the first-person singular pronoun _I_ only in the question part of FAQs and when authors of blogs or signed articles are describing their own actions or opinions.

| Use | Avoid |
|--|--|
|To create a server, specify a name, flavor, and image. (imperative)<br>To create a server, you specify a name, flavor, and image.| Creating a server involves specifying a name, flavor, and image.<br>To create a server, the user specifies and name, flavor, and image.|
|Click **Yes** to accept the license agreement.|The license agreement is accepted when the user clicks **Yes**.|
|We offer you a comprehensive portfolio of quantitative options.|QuantConnect offers a comprehensive portfolio of hosting options for the enterprise buyer.|
|Object Store uses block-level deduplication, which means that only those parts of a file that have changed are saved.|Object store uses block-level deduplication, which means we save only those parts of a file that have changed.|
| I want to update everyone about the current status of the project and our future plans. (from a blog post) | This post describes the current status of the project and future plans. |

## Write clear and concise sentences and paragraphs
Use the following guidelines to help you write clear and concise sentences and paragraphs.

#### Use a consistent sentence structure
As often as possible, use the sentence structure _subject_—_verb_—_object_. Use simple declarative sentences for descriptions, and use simple imperative sentences for instructions. However, you can use a sentence structure that starts with an _if_ clause or places the condition before the action.
| Use | Avoid |
|--|--|
|The value is truncated only when it's stored in an integer value.|Only when stored in an integer variable is the value truncated.|
|To bake a cake, follow these steps.|Take a look at the following procedure below to bake a simple cake.|
|If you must monitor algorithms from a host, you can configure your settings in the directory.|You can configure your settings in the directory if you must monitor algorithms from a host.|

#### Restrict sentence length
Even a well-written long sentence can be hard to follow and understand. Try to limit sentences to 20-25 words. If you must write a longer sentence, it should have more than one clause, and the relationship between the clauses should be clear.
| Use | Avoid |
|--|--|
|After you choose a data center, the app retrieves a list of the containers that are hosted within that data center. The number of files in each container and the approximate size of each container are displayed.|After you choose a data center, the app retrieves a list of the containers that are hosted within that data center, along with the number of files in each container and the approximate size.|
|Select whether to overwrite files with the same name or to restore files to their original folders. Then, click **Next**.|Click the check boxes to confirm whether you would like to Overwrite files with the same name or restore the files to their original folders and then click the **Next** button.|

#### Use only, but all of, the necessary words
Technical writing isn't creative writing. Use only the words necessary to convey the meaning, and strip out anything extraneous. For example, are you using adverbs (modifiers ending in  _-ly_)? If so, you can remove most of them without changing the meaning. What about adjectives? If they aren't necessary to the meaning, remove them. Can prepositional phrases be shortened? Are you using empty phrases that don't clarify the content?

Conversely, be sure to include all the words that  _are_  necessary to make the meaning of a sentence clear. Include all necessary articles (_a_,  _an_,  _the_), prepositions, connectors, and other syntactic cues, such as those described in  [Clarify gerunds and participles](https://docs.rackspace.com/docs/style-guide/writing/clarify-gerunds-participles/#clarify-gerunds-participles),  [Use that, which, and such as correctly](https://docs.rackspace.com/docs/style-guide/writing/use-that-which-correctly/#use-that-which-correctly), and  [Use pronouns carefully](https://docs.rackspace.com/docs/style-guide/writing/use-pronouns-carefully/#use-pronouns-carefully).

| Use | Avoid |
|--|--|
|You can use the product to generate temporary URLs for files and share the files with other people.| A great feature implemented by the product is the ability to generate temporary URLs for files and share them with other people.|
|Use the Organization Dashboard to create servers easily and quickly.|The well-designed Organization Dashboard is your passport to creating servers in an easy, fun way right away.|
|Empty the file.|Empty file.|
|The Label option isn't supported for this file format.| Label option not supported for file format.|

#### Create short paragraphs
Short paragraphs are easier to scan and understand than longer ones. Use the following guidelines for paragraphs:

-   Cover only one idea in each paragraph.
-   Limit paragraphs to four to five sentences. However, avoid having one-sentence paragraphs.
-   Use connective or transitional words to ensure flow within and between paragraphs.
-   When listing three or more items, use a bullet list instead of embedding the items in a paragraph.

| Use | Avoid |
|-------------------|-------------------|
|From the Job Scheduler window, you can perform the following actions:<ul><li>Run a generated script immediately.</li><li>Schedule a generated script to run at a later time.</li><li>Track the execution of submitted jobs.</li><li>Manage jobs in the job queue.</li><ul>|From the Job Scheduler page, you can run a generated script immediately, schedule a generated script to run at a later time, track the execution of submitted jobs, and manage jobs in the job queue.|

## Use effective verbs
Verbs carry the action in a sentence, and they make your content come alive for users. To make the biggest impact with your writing, use strong, simple, action verbs. See the following sections for specific guidelines.

####  Use action-oriented verbs
Verbs are supposed to carry the action in a sentence. However, when you use verbs like  _be_,  _have_,  _make_, or  _do_  (and their variants), or when you use gerunds (_-ing_  words), nouns carry the action and weaken the meaning. Shift the focus from nouns to verbs by replacing weak verbs and gerunds with strong, action-oriented verbs. Relying on verbs rather than nouns usually makes sentences shorter, clearer, and more direct.

| Use | Avoid |
|-------------------|-------------------|
|QuantConnect **leads** the industry. | QuantConnect **is** the industry leader |
| Organization Permissions **restricts** service access to authorized users. | Organization Permissions **are** a method of restricting service access to authorized users.|
| If the node **can't access the Internet**, the installation process fails.|If the node **doesn't have Internet access**, the installation process fails.|
|To create a server, **specify** a name, flavor, and image.|You create a server **by specifying** a name, flavor, and image.|
|When you **create** a server, ...|When **creating** a server, ...|

#### Avoid nouns built from verbs
Many nouns are built from verbs, for example, _description_ and _explanation_. Such nouns are called _nominalizations_. Sentences that include a nominalization _and_ a verb can often be simplified by changing the nominalization back into a verb and omitting the existing verb (as shown in the following examples).
| Use | Avoid |
|-------------------|-------------------|
|The following table **describes** each of the products.|The following table **provides a description of** each of these products.|
|**Install** the product by completing the following tasks.|**Perform the installation** of the product by completing the following tasks.|
|The program **encrypts** user IDs and passwords.|The program **enables the encryption of** user IDs and passwords.|

#### Use the simplest tense
 Simple verbs, such as verbs in the present tense, are easier to read and understand than complex verbs, such as verbs in the progressive or perfect tense, or verbs combined with helping verbs (such as _can_, _may_, _might_, _must_, and _should_).
 | Use | Avoid |
|-------------------|-------------------|
|Before you perform this task, **complete** the prerequisites.|Before you perform this task, you **should have completed** the prerequisites.|
|To start, three ports **are** open: ssh, http, and https.|To start, you **are going to have** three ports open: ssh, http, and https.|
|If you **use** a Red Hat distribution, iptables works a little differently.|If you **are using** a Red Hat distribution, iptables works a little differently.|

#### Use helping verbs accurately
If you need to use the following helping verbs, use them accurately and consistently:

-   **Can**: Use  _can_  to indicate the ability to perform an action.
-   **May**: Use  _may_  to indicate permission.
-   **Might**: Use  _might_  to indicate probability or possibility.
-   **Must**: You can use  _must_  to indicate the necessity of an action. However, in general, use the imperative mood, which implies the subject  _you_  and doesn't require  _must_  but still indicates necessity.
-   **Should**: Use  _should_  to tell users what they  _ought_  to do. Because  _should_  implies uncertainty, avoid using it unless you explain further. 

| Use |
| -- |
|You  **can**  customize Organizations to achieve a wide range of performance, availability, and efficiency goals.|
|If you need space, you  **may**  uninstall the program.|
|A service  **might**  expose endpoints in different regions.|
|The worker  **must**  delete the message when work is done.|
|To avoid losing a file update in the middle of saving, clients  **should**  periodically save during long-running batches of work.|

#### Use single-word verb

When possible, use single-word verbs rather than phrasal verbs (verbs followed by prepositions or adverbs). For example, use  _omit_  rather than  _leave out_, or shorten  _start up_  to  _start_. One-word verbs are easier to understand and to translate.

If you must use a phrasal verb, keep the parts of the verb together unless that changes the meaning of the sentence. Some acceptable phrasal verbs are  _back up_,  _log in_,  _set up_,  _shut down_, and  _work around_. 

Don't turn a phrasal verb into a single-word verb. For example, don't use _login_, _setup_, or _workaround_ as verbs. These single-word terms should be used only as nouns or adjectives.

| Use | Avoid |
|-------------------|-------------------|
|**Determine** the type of encryption (32-bit or 64-bit) that your computer uses.|**Figure out** the type of encryption (32-bit or 64-bit) that your computer uses.|
|**Click** the link.|**Click on** the link.|
You can safely **back up a database** by using the Obect Store.|You can safely **back a database up** by using Object Store.|

#### Don't use verbs as nouns or adjectives

If a word is defined in the dictionary as a verb, don't use it as a noun or adjective. Some verbs that are commonly misused as nouns or adjectives are  _configure_,  _compile_,  _debug_, and  _install_.

| Correct | Incorrect |
|-------------------|-------------------|
|After **installation** is completed, you can **configure** the product.|When you complete the **install**, you can begin the **configure**.|
|After rubygems **is compiled**, the following message appears at the bottom of the output text.|When the **compile process** is finished, the following message appears at the bottom of the output text.|

#### Don't use nonverbs as verbs 

Don't use nouns or adjectives as verbs, and don't add verb suffixes to abbreviations, nouns, or conjunctions.
| Correct | Incorrect |
|-------------------|-------------------|
|You can **reorganize** the table space.| You can **REORG** the table space.|
|Verify the change **by using the ping command** to contact the server.|Verify the change **by pinging** the server.|
|Some databases and search engines **insert the AND operator** between adjacent words in a keyword search.|Some databases and search engines **AND** adjacent words in a keyword search.|
|**Navigate** to the new directory.| **CD** to the new directory.|

#### Use transitive verbs transitively, not intransitively

Transitive verbs, such as  _display_  and  _complete_, require a direct object. Intransitive verbs don't require a direct object. Be sure to use each type of verb correctly.

To avoid using a transitive verb intransitively, you can make it passive if the performer of the action is understood or not important.
| Correct | Incorrect |
|-------------------|-------------------|
|The product  **displays**  the available servers in the right pane.<br/>_or_<br>The available servers  **are displayed**  in the right pane.| The available servers **display** in the right pane.|
|After the installation **is completed**, ensure that the FTP services are running.|After the installation **completes**, ensure that the FTP services are running.|

#### Don't humanize inanimate objects

Be careful not to ascribe human feelings, motivations, and actions to inanimate objects. For example, a software program doesn't know, need, remember, see, think, understand, or want. However, it can detect, record, require, store, check, calculate, and process.

The following anthropomorphic verbs are acceptable in the computer industry: accept, calculate, deny, detect, interact, interpret, listen, refuse, read, and write.

| Use | Avoid |
|-------------------|-------------------|
|When you reference your modules in your script by using a PHP function like `include()` or `require()`, the server **can find** them. | When you reference your modules in your script by using a PHP function like `include()` or `require()`, the server **knows where to look for** them.|
|Mission-critical web-based applications and workloads **require** an HA solution.|Mission-critical web-based applications and workloads **need** an HA solution.|
|The software **stores** your security profile and uses it the next time you log in.|The software **remembers** your security profile and uses it the next time you log in.|

## Tasks
A _task_ is an action that users perform to achieve a goal, such as creating a server. A task topic, article, or section provides the action steps and the necessary context and reference information that the user needs to complete the task.

#### Task titles
The title of a task topic, article, or section begins with the imperative form of the task action, and it uniquely, precisely, and clearly describes the task. Use a plural subject unless the singular makes more sense or is necessary for clarity.

**Examples**
-   Create users in SQL Server
-   Configure SQL Server Management Studio to connect to SQL Server on Windows
-   Add new ServiceNet routes to a server

#### Task introductions
Before providing steps, set the context for the task as necessary. For example, you could state the reason for completing the task, the method to be used, and the expected result. You might also state the intended audience and suggest the amount of time that the task might take, especially if it will take a long time.

**Notes:**

- If the article or section title provides sufficient context, you can omit an introduction.
- Avoid providing extensive overview or conceptual text in the introduction to a task. Provide that information in a separate informational topic or in a topic that introduces the task as part of a larger process or user goal.

#### Prerequisites 
If the task has requirements that the user  _must_  meet before taking action, describe them in a "Prerequisites" section that precedes the steps. You could include the following information:

-   A hyperlink to a preceding task, if that task must be performed before this task
-   Software that must already be installed, accessible, or running
-   Access rights that are required for users to perform the task
-   Hyperlinks to other topics that contain requirements or prerequisite tasks that the user must perform

Avoid including detailed procedures in a prerequisites section. Provide prerequisite tasks in other articles or sections, which you can reference in this section.

#### Procedures
A task contains one or more  _procedures_, or set of sequential action steps. Consider the following guidelines when creating a procedure:
-   If the procedure has more than one step, use a numbered list for the steps. Don't use bullets, except to list choices within a step.
-   If the procedure has only one step, show that step in a regular paragraph. That is, don't number it.
-   If you have lengthy introductory or prerequisite information, or if you have more than one procedure, provide a heading for the procedure or procedures. Use the imperative form of the action and a singular form of the object. Don't repeat the title of the task article.
-   Try to limit procedures to 10 steps. If you have more than 10 steps, consider whether you can divide the steps into two or more procedures. Creating several short, simple, and sequential procedures instead on one long, complex procedure, especially one with many substeps and choice steps, will help users know where they are in the process, judge their progress, and complete the task successfully.

#### Task steps
When writing steps, use the following guidelines.

##### Use imperative sentences 
Write each step as a complete and correctly punctuated imperative sentence (that is, a sentence that starts with an imperative verb). In steps, the focus is on the user, and the voice is active.

**Examples** 
1.  Log in to your QuantConnect Organization. 
2.  Use the following command to get your user ID  `uid`.

##### Provide context before the action
If a step specifies where to perform an action, state where to perform the action before describing the action.

**Examples**
1.  In the terminal console, click  **Build**.
2.  On the Organization Members page, perform the following steps:

##### Provide conditions before actions
If a step specifies a situation or a condition, state the situation or condition before describing the action.

**Examples**
1.  If a new version is available, click  **Install**.
2.  To find out the encryption type of your Windows computer (32-bit or 64-bit), navigate to the server's Control Panel and click  **System**.

##### Follow the step with explanatory information
Don't include explanatory or reference information in the action part of a step. If needed, follow the step with one or more paragraphs that provide supplemental information.

**Examples**
1.  In the  **Rename File**  text box, enter a name for the file including its path location in the project.
    For example, you can name a file with `/libraries/math/addition.py` to store it in subfolders.

##### Show only actions as steps 
Don't show system actions, responses, or results as steps. Put necessary statements in unnumbered paragraphs following the steps to which they apply. See the first example in the "Examples" section.

When the result of a step is the appearance of a dialog box, window, or page in which the action of the next steps occurs, you can usually eliminate a result statement and orient the user at the beginning of the next step. See [examples](https://docs.rackspace.com/docs/style-guide/style/tasks/#show-only-actions-as-steps).

##### Use screenshots sparingly 

Screenshots can help to orient the user, but a screenshot of every field or dialog box usually isn't necessary.

If you include screenshots, place each one directly under the step that it illustrates. Don't rely on the screenshot to show information or values that the user must enter; always provide that information in the text of the steps. However, ensure that the screenshot accurately reflects the directions and values in the step text. 

##### Label optional steps 
To indicate that a step is optional, include  _(Optional)_, in italics, as a qualifier at the beginning of the step.

| Use | Avoid |
|-------------------|-------------------|
| _(Optional)_  Click  **Advanced Options**. |  Optionally click  **Advanced Options**. | 

##### Omit extraneous words 
Omit extraneous words (such as  _pop-up menu_  or  _command button_) unless they're needed for clarity.

| Use | Avoid |
|-------------------|-------------------|
|1.  In the Disks window, right-click the volume and select  **Take Offline**.|1.  In the Disks window, right-click the volume and select  **Take Offline**  from the pop-up menu.|
|1.  Click  **Add**, enter a name for the profile, and then click  **OK**.|1.  Click the  **Add**  button, enter a name for the profile in the text box, and then click the  **OK**  button.|

##### Show multiple possibilities in a list
If a step directs the user to choose from multiple possibilities, use an unordered list to present the possibilities.

**Example**

1.  Select a volume type:
    -   **Standard**: A standard SATA drive for users who need additional storage on their server.
    -   **High Performance**: An SSD drive, which offers a higher performance option for databases and high performance applications.

##### Document only one method
If more than one method exists for completing an action, document only one method, usually the most efficient or preferred method.
| Use | Avoid |
|-------------------|-------------------|
|1.  Select  **File > New**.|1.  Select  **File > New**, or press  **Ctrl+N**.|

##### Results, verification, examples, and troubleshooting 
Following the procedure or procedures, include the following information if it's necessary or helpful to the user. If the information is brief, you can include it directly following the last step in the procedure. If it's lengthy or you need to provide more than one type of information, use sections with headings.

-   The result of performing the task.
-   Information about verifying successful completion of the task, such as the location of logs. If verification is a separate task in a different article or section, provide a hyperlink to it under a "Where to go from here" heading.
-   An example that illustrates or supports the task.
-   Information about what to do if the procedure doesn't work. This information might be a hyperlink to a separate troubleshooting topic.

##### Direction to the next action
If your task is part of a larger set of tasks, you can help the user by including a "Where to go from here" section. You might include the following information:

-   A brief explanation of the next task and why the user needs to perform it, accompanied by a hyperlink to the next task.
-   Hyperlinks to other tasks that could be done next, if multiple options are available. Describe the multiple options so that users know which task to choose.

##### Related topics 
To provide a quick way for the user to access other content that's related to the task, provide links to the content at the end of the article or topic. Even if you have already included an embedded hyperlink to the material in the article or topic, you can provide the hyperlink again under "Related topics," but typically you should provide a link only once in an article or section.

## Clarify pronouns
Pronouns are useful, but you must ensure that their antecedents (the words that they are used in place of) are clear, and that they (the pronouns) don’t cause vagueness and ambiguity.

#### It
Ensure that the antecedent of  _it_  is clear. If multiple singular nouns precede  _it_, any of them could be the antecedent.
Avoid using  _it is_  (or  _it's_) to begin a sentence. Such a construction hides the real subject of the sentence.
| Use | Avoid |
|-------------------|-------------------|
|You can store the value and use it again later.|The product stores the value in the configuration file. You can use it again later. (The antecedent of it could be the product, the value, or the file.)|

#### This
Avoid beginning a sentence with the pronoun  _this_, unless you follow  _this_  with a noun to clarify its meaning.
| Use | Avoid |
|-------------------|-------------------|
|This option causes an error when you run the program.|This causes an error when you run the program.|
|The LEAN SDK supports resumable uploading. If a connection fails...|The LEAN SDK service supports resumable uploading. This means that if there is a connection failure...|

#### There
Avoid using  _there is_  and  _there are_  as the subject of a sentence or clause. Using  _there_  shifts the focus away from the real subject and often uses unnecessary words.
| Use | Avoid |
|-------------------|-------------------|
|This option has no parameter.<br/>_or_<br/>No parameter exists for this option.|There is no parameter for this option.|
|When *errors* occur in the script, the product writes information to the message log.|When *there are* errors in the script, the product writes information to the message log.|


#### That
Although you should use _that_ as a relative pronoun, avoid using it as a demonstrative pronoun (which stands in for or points to a noun). Instead, use it as an adjective and follow it with a noun.
| Use | Avoid |
|-------------------|-------------------|
|Use that method.|That is the method to use.|
|You can also edit or delete your CNAME by managing your DNS in your existing tool.|If you want to edit or delete your CNAME, you can also do that by managing your DNS in your existing tool.|

## Use correct punctuation
When you use correct punctuation, you help users understand the content  _the first time_  they read it. Following are a few basic guidelines to apply:

-   Use a period at the end of sentences, even imperative ones (such as steps).
-   Use a comma before the last item in a series (known as the  _serial_  comma).
-   Use a comma to separate independent clauses, and include a coordinating conjunction (such as  _and_).
-   Avoid using semicolons. You can almost always use a period in the place of a semicolon.
-   Don't use a slash (/) to present a choice among, or a series of, actions or objects. Rewrite the phrase to eliminate the slash mark. Exceptions are established terms like  _client/server_  and  _read/write_.
-   Avoid using exclamation points, question marks, ellipses, or single quotation marks in regular text. Although these punctuation marks might appear in code elements, messages, literal commands, or UIs, they're rarely useful when writing descriptions or instructions for users. One exception is the use of question marks in FAQ topics.

## Titles and headings 
This topic provides guidelines for creating titles and headings in documentation.

#### Capitalization

Use  _sentence-style_  capitalization for most titles and headings, including article, chapter, table, figure, and example titles, as well as section and procedure headings.

One exception is guide titles, which use  _title-style_  capitalization.

##### Guidelines for sentence-style capitalization 

In sentence-style capitalization, you capitalize only the first word of the title or heading, plus any proper nouns, proper adjectives, and terms that are always capitalized, such as some acronyms and abbreviations. If the title includes a colon, capitalize the first word that follows the colon, regardless of its part of speech.

If the heading includes text from a user interface, the capitalization of that text must match the capitalization on the interface.

##### Guidelines for title-style capitalization 

Title-style capitalization uses initial uppercase letters for the first, last, and all the significant words in the title.

Capitalize all words in the title except for the following types of words:

-   Articles (_a_,  _an_,  _the_) unless the article is the first word in the title or follows a colon
-   Coordinating conjunctions (_and_,  _but_,  _for_,  _nor_,  _or_,  _yet_,  _so_) unless the conjunction is the first word in the title
-   Prepositions of any length, unless the preposition is the first or the last word in the title or is part of a verb phrase
-   The word  _to_  in an infinitive phrase unless to is the first word in the title
-   Second elements attached by hyphens to prefixes unless they're proper nouns or proper adjectives
-   Words that always begin with a lowercase letter, such as literal command names or certain product or software names

| Examples |
| -- |
| DNS Getting Started Guide |
| Stand-alone Object Storage Guide |
| QuantConnect Private Lab Customer Handbook |
| QuantConnect Cloud Release Notes|

#### Style and structure 

Use the guidelines in this section to create effective and consistent titles and headings. The following guidelines apply to all titles and headings; special considerations for stand-alone articles, product guides, and tables, figures, and examples follow this list.

-   Create succinct, meaningful, descriptive titles and headings, and place the most important words first.
    
-   Ensure that each title and heading is unique within a given content set.
    
-   Include articles, prepositions, and punctuation as needed for clarity. However, avoid using an article (_a_,  _an_, or  _the_) as the first word.
    
-   Avoid showing both an abbreviation and its spelled-out term in a title or heading. To help control the length of titles and headings, show the abbreviation in the title or heading and then define it in the first paragraph of the text.
    
-   If you show a literal term (such as a command or option name) in a title or heading, follow it with an appropriate noun.
    
-   Don't end a title or heading with a colon or period. If the title or heading is in the form of a question, end it with a question mark.
    
-   Don't apply font treatments (bold, italics, or monospace) to text in a title or heading.
    
-   Don't include trademark symbols in titles or headings. Show the symbol on the first use of the trademark in text.
    
-   Avoid having only a single heading at any level (for example, a single subsection in an article or section). If you find that you have a single heading at any one level, consider whether you can reorganize the information to either eliminate the heading or add a second one at that level.
    
-   Avoid having more than two levels of sections within an article or topic. If you use more than two levels of sections, consider whether you can reorganize to make the structure flatter.
    
-   Don’t "stack" titles or headings. That is, don’t immediately follow a title or heading with another title or heading. Text should always intervene between them. Ensure that such text is meaningful. If it is just filler text, consider whether you can restructure the content.
    
-   Use a consistent grammatical structure for the titles and headings of specific types of content:
 
| Type | Grammatical structure | Stand-alone article examples | Product guide examples |
| -- | -- | -- | -- |
| Conceptual | Any grammatical structure that's appropriate, except a verb, gerund, or infinitive| Linux distributions | Core concepts | 
|Step-by-step instructions| An imperative verb | Identify network interfaces on Linux | Sign up for a QuantConnect account |
| Tutorial | A gerund | Customizing Apache web logs | Working with your first message queue |
| Reference | A plural noun or a noun phrase | Permissions matrix for QuantConnect Organizations | Environment variables for parameter optimization | 
| Troubleshooting | A grammatical structure that's appropriate for the type of content (a troubleshooting topic can contain task, tutorial, concept, or reference information) | Service troubleshooting on Linux | Troubleshooting |
| FAQ | A descriptive noun or noun phrase, followed by _FAQ_ | QuantConnect Billing FAQ | Scheduled Event FAQ |

#### Stand-alone articles

In addition to the preceding guidelines, use the following guidelines when creating titles and headings for stand-alone articles on the Support site or in other collections of documentation:

-   Create article titles that don’t rely on body text or other titles for their meaning (that are, in other words, independent of context). Users should be able to tell from a title whether the information in the article is relevant to their needs. Avoid ambiguous one-word titles, such as "Overview."
-   Don't number titles to indicate their placement in a series of articles. Indicate the order of articles within the content of the article, referring users to information that they should have read previously before reading the current article. Use links to provide navigation to preceding and following articles in the series.
-   Start with the highest level of heading that is approved for headings (for example, h3), and do not skip heading levels.

#### Product guides

In addition to the preceding guidelines, use the following guidelines when creating titles and headings for sections in product guides:

-   If possible, limit titles and headings to 60 characters for legibility in the TOC pane.
-   Consider that titles and headings are written within the context of the content set in which they are presented. Therefore, you can usually omit "context-setting" terms. For example, if the content set is about servers, you can usually omit "for servers" from the title or heading. (For example, "Attach a network to a server" can be shortened to "Attach a network" with no loss of clarity.)
-   Define consistent heading levels, and do not skip levels.

#### Tables, figures, and examples

As a general rule, tables, figures, and examples should have titles (also called captions). However, tables, figures, and examples in procedures and tutorials don't normally require titles.

In addition to the preceding guidelines, use the following guidelines when creating titles for tables, figures, and examples:

-   Place the title above the table, figure, or example, not below it.
-   Tag the title as bold.
-   Avoid using a title that duplicates an article or section title.

#### Text following titles and headings

Don’t immediately follow a title or heading with another title or heading. Instead, follow a title or heading with body text.

The body text must be independent from the title or heading. Don't use a title or heading as an antecedent in the sentence that follows it. That is, be sure to repeat the subject in the first sentence that follows the title or heading, rather than using a pronoun that refers to the title or heading as its antecedent.

### Code examples
When you create blocks of code as input or output examples, follow some basic guidelines to make them clear to users.
Use the following guidelines when creating blocks of code as input or output examples:

-   Don't use screenshots to show code examples. Format them as blocks of code by using the `<code></code>` switches when providing inline snippets, or use the following example for complex code blocks. 
```
<div class="section-example-container">
	<pre class="python"></pre>
	<pre class="csharp"></pre>
</div>
```
-   When showing input, always include a command prompt (such as $).
    
-   As often as necessary, show input and output in separate blocks and provide explanations for each. For example, if the input contains arguments or parameters, explain those. If the user should expect something specific in the output, or you want to show only part of lengthy output, provide an explanation.
    
-   When the command is simple, and there's nothing specific to say about the output, you can show the input and output in the same code block, as users would actually see the code in their own terminal. The inclusion of the command prompt differentiates the input from the output.
    
-   Ensure that any placeholder text in code is obvious.
    
    -   If the authoring tool allows it, apply italics to placeholders; if not, enclose them in angle brackets.
        
    -   Use lowercase letters for single-word placeholders. To show multiple-word placeholders, don't separate the words with spaces or symbols and capitalize the first letter of each word after the first word (camelCase).

-   Follow the conventions of the programming language.
    
-   For readability, you can break up long lines of input into readable blocks by ending each line with a backslash.
    
-   If you explain the arguments or parameters in text, show them in the same order that they appear in the code block.

### Use consistent and simple terminology
Use short, simple words, and use them as they are defined in a general or accepted industry dictionary. Each word or phrase should have only one meaning that is used consistently throughout the content. Avoid using humor, jargon, and metaphors.

 - [Use consistent terminology](style-guide-consistent-terminology.md)
 - [Terminology for a global audience](style-guide-terminology-global-audience.md)
 - [Concise terms](style-guide-concise-terms.md)


## License
Work in the `/Style Guide/` folder is derived [Rackspace Style Guide](https://github.com/rackerlabs/docs-style-guide) and made available in the same Creative Commons Attribution 4.0 International License.
